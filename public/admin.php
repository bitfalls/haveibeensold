<?php

require_once '../app/config/db.php';
require_once '../app/mail.php';

$pdo = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$emailhash  = $_GET['h'] ?? null;
$actionhash = $_GET['a'] ?? null;

if (!$emailhash || !$actionhash) {
    die("This link is not valid.");
}

$stmt = $pdo->prepare('SELECT * FROM emails WHERE hash = :hash');
$stmt->execute(['hash' => $emailhash]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result)) {
    switch ($actionhash) {
        case generateactionhash('confirm', $result[0]['email']):
            $stmt   = $pdo->prepare('UPDATE emails SET `hash` = "ok" WHERE `hash` = :hash');
            break;
        case generateactionhash('remove', $result[0]['email']):
            $stmt   = $pdo->prepare('DELETE FROM emails WHERE `hash` = :hash AND `list_id` = "manual"');
            break;
        default:
            break;
    }
} else {
    die("No matching entry found.");
}

$return = $stmt->execute(['hash' => $emailhash]);
if ($return) {
    die("You're all set!");
} else {
    die("Something went wrong :( Tell us what happened at contact@bitfalls.com");
}