<?php

require_once '../../app/config/db.php';
require_once '../../app/mail.php';

$pdo = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$email = $_POST['email'] ?? null;
if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
    ret(0, 'E_NOT_VALID');
};

$email = strtolower($email);

switch ($_POST['action']) {


    case 'check':

        try {
//            $query = "SELECT list_id FROM `emails` e LEFT JOIN `list` l ON l.`id` = e.`list_id` WHERE e.email = :email ";
            $query = "SELECT e.*, l.*, e.name as contactname FROM `emails` e LEFT JOIN `list` l ON l.`name` = e.`list_id` WHERE e.email = :email AND l.`name` != 'manual'";
            $statement = $pdo->prepare($query);
            $statement->execute(['email' => $email]);
            $result = $statement->fetchAll();

            foreach ($result as $i => &$r) {
                $date  = date("M Y", strtotime($r['obtained_date']));
                list($heading, $description) = explode('--||--', $r['description']);
                $r['info'] = "<h4>".($i+1).": ". $heading ." (Obtained ". $date .")</h4><p>".$description."</p><p>The list was originally obtained in ".$date.".</p><p>Listed as ".$r['contactname']." with the following info: ".$r['extra']."</p>";
            }

            ret(1, $result);
        } catch (\Throwable $e) {
            ret(0, 'E_QUERY_FAILED: '.$e->getMessage());
        }
        break;






    case 'add':

        $query = "SELECT * FROM emails WHERE email = :email AND list_id = 'manual'";
        try {
            $statement = $pdo->prepare($query);
            $statement->execute(['email' => $email]);
            $result = $statement->fetchAll();
            if (count($result)) {
                ret(0, 'E_ALREADY_LISTED');
            }
        } catch (\Throwable $e) {
            ret(0, 'E_QUERY_FAILED: '.$e->getMessage());
        }

        $hash = generateemailhash($email);

        $query = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`) VALUES (:email, :list, :notified, :hash)";
        try {
            $stmt = $pdo->prepare($query);
            $insertion = $stmt->execute([
                'email' => $email,
                'list' => 'manual',
                'notified' => 0,
                'hash' => $hash
            ]);
            if ($insertion) {
                if (send_confirm_mail($email, $hash)) {
                    ret(1, 'OK');
                } else {
                    ret(0, 'E_SEND_FAIL');
                }
            } else {
                ret(0, 'E_INSERT_FAIL');
            }
        } catch (\Throwable $e) {
            ret(0, 'E_QUERY_FAILED: '.$e->getMessage());
        }
        break;











    case 'del':
        try {
            $query = "SELECT * FROM emails WHERE email = :email AND `list_id` = 'manual'";

            $statement = $pdo->prepare($query);
            $statement->execute(['email' => $email]);
            $result = $statement->fetchAll();

            if (count($result) == 0) {
                ret(1, "NOT_THERE");
            } else {
                $emailhash = generateemailhash($email);
                $statement = $pdo->prepare("UPDATE emails SET `hash` = :hash WHERE id = :id");
                $return    = $statement->execute([
                    'hash' => $emailhash,
                    'id'   => $result[0]['id']
                ]);

                if (send_remove_mail($email, $emailhash)) {
                    ret(1, 'OK');
                } else {
                    ret(0, 'E_SEND_FAIL');
                }

            }
        } catch (\Throwable $e) {
            ret(0, 'E_QUERY_FAILED');
        }
        break;
    default:
        break;
}













function ret($type, $message) {
    header('Content-type:application/json;charset=utf-8');
    die (json_encode([
        'status' => $type ? 'success' : 'error',
        'data' => $message
    ]));
}