<?php

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require_once '../vendor/autoload.php';
require_once '../app/config/db.php';
require_once '../app/mail.php';

$pdo = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

$files = [
    __DIR__ . '/../data/20k-list.xlsx',
    __DIR__ . '/../data/ap-p2p-event.csv',
    __DIR__ . '/../data/consensus-2017.xlsx',
    __DIR__ . '/../data/lendit.xlsx',
];

$ifs = [
    '20k'        => 1,
    '20k-8.8'    => 1,
    '20k-ico'    => 1,
    '20k-earn'   => 1,
    '20k-earn2'  => 1,
    '20k-inv'    => 1,
    '20k-angel'  => 1,
    '20k-asian'  => 1,
    '20k-cryptw' => 1,
    'p2p'        => 1,
    'con17'      => 1,
    'lendit'     => 1,
];

if ($ifs['20k']) {
    try {

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($files[0]);
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files[0]);
    } catch (\Throwable $e) {
        die($e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-8.8']) {
    try {
        $sheet = $spreadsheet->getSheet(1);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '0' => 'twitter',
            '1' => 'name',
            '2' => 'description',
            '5' => 'email',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '8.8k-btc', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $row) {

            $row = trimrow($row);

            $email = $row[5];
            if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $extra = '';
            if ($row[0] ?? false) {
                $extra .= twitterlink($row[0]) . " on Twitter.";
            }

            $statement->execute([
                'email' => $email,
                'name'  => trim($row[1]),
                'extra' => $extra,
            ]);
        }


    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-ico']) {
    try {
        $sheet = $spreadsheet->getSheet(2);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '4' => 'title',
            '5' => 'description',
            '6' => 'name',
            '7' => 'position',
            '8' => 'email',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '20k-ico', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $row) {

            $row = trimrow($row);
            $email = $row[8];

            if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $extra = '';
            if ($row[7] ?? false) {
                $extra .= $row[7];
            }
            if ($row[4] ?? false) {
                $extra .= ' of ' . $row[4];
            }
            if ($row[5] ?? false) {
                $extra .= ', ' . $row[5];
            }

            $statement->execute([
                'email' => $email,
                'name'  => $row[6],
                'extra' => $extra,
            ]);
        }
    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-earn']) {
    try {
        $sheet = $spreadsheet->getSheet(3);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '2'  => 'price',
            '12' => 'username',

            '3' => 'github',
            '4' => 'linkedin',
            '5' => 'twitter',
            '6' => 'facebook',

            '13' => 'company',
            '14' => 'title',

            '15' => 'name',

            '16' => 'vip',

            '19' => 'category',

            '26' => 'lists',

            '27' => 'email',

            '28' => 'vc',
            '29' => 'angel',
            '30' => 'investor/trader',
            '31' => 'media/journalist',
            '32' => 'blockchain dev/engineer',
            '33' => 'crypto CEO / executive',
            '34' => 'banker',
            '35' => 'consultant',
            '36' => 'other tech services',
            '38' => 'software engineer',
            '39' => 'marketing / sales / account exec',
            '40' => 'executive',
            '41' => 'entrepreneur',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '2.3-21co', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $row) {

            $row   = trimrow($row);
            $email = $row[27] ?? null;

            if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $extra  = '';
            $row[2] = ! empty($row[2]) ?: "0";
            $extra  .= "The default contact price for your " . ($row[16] == 'vip' ? 'VIP ' : '') . "account {$row[12]} is listed as $" . $row[2] . ". ";

            if ($row[3] ?? false || $row[4] ?? false || $row[5] ?? false || $row[6] ?? false) {
                $extra .= 'Your social accounts are listed as: ' . implode(", ",
                        array_filter([
                            ($row[3] ?? false) ? githublink($row[3]) : '',
                            ($row[4] ?? false) ? justlink($row[4]) : '',
                            ($row[5] ?? false) ? twitterlink($row[5]) : '',
                            ($row[6] ?? false) ? justlink($row[6]) : '',
                        ]));
                $extra .= '. ';
            }
            if ($row[13] ?? false && $row[14]) {
                $extra .= 'You are listed as ' . $row[14] . ' at ' . $row[13] . '. ';
            }
            if ($row[19] ?? false) {
                $extra .= 'Your category is listed as ' . $row[19] . '. ';
            }

            $extra .= 'Additionally, 21.co (Earn.com) tagged you as: ';
            foreach (
                [
                    '28' => 'vc',
                    '29' => 'angel',
                    '30' => 'investor/trader',
                    '31' => 'media/journalist',
                    '32' => 'blockchain dev/engineer',
                    '33' => 'crypto CEO / executive',
                    '34' => 'banker',
                    '35' => 'consultant',
                    '36' => 'other tech services',
                    '38' => 'software engineer',
                    '39' => 'marketing / sales / account exec',
                    '40' => 'executive',
                    '41' => 'entrepreneur',
                ] as $k => $v
            ) {
                if ($row[(int)$k] == 'x') {
                    $extra .= $v . ', ';
                }
            }

            $extra = trim($extra, ', ');

            $statement->execute([
                'email' => $email,
                'name'  => $row[15],
                'extra' => $extra,
            ]);
        }
    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-earn2']) {
    try {
        $sheet = $spreadsheet->getSheet(4);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '0' => 'name',
            '1' => 'price',

            '2' => 'pos1',
            '3' => 'company',
            '5' => 'pos2',
            '6' => 'pos3',

            '27' => 'location',

            '29' => 'github',
            '30' => 'linkedin',
            '31' => 'twitter',

            '33' => 'vc',
            '34' => 'angel',
            '35' => 'investor/trader',
            '36' => 'altcoin investor',
            '37' => 'executive',
            '38' => 'media/journalist',
            '39' => 'blockchain dev/engineer',
            '40' => 'crypto CEO / executive',
            '41' => 'banker',
            '42' => 'consultant',
            '43' => 'other tech services',
            '46' => 'software engineer',
            '47' => 'marketing / sales / account exec',
            '44' => 'a cryptocurrency or exchange',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '3.4-21co', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        $skips  = 0;
        $skips1 = 0;
        foreach ($sheet as $row) {

            $row = trimrow($row);

            $emails = [];
            if ( ! empty($row[24])) {
                $emails[] = $row[24];
            }
            if ( ! empty($row[25])) {
                $emails[] = $row[25];
            }
            $emails = implode(',', $emails);
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra  = '';
                    $row[1] = ! empty($row[1]) ?: "0";
                    $extra  .= "The default contact price for your account is listed as $" . $row[1] . ". ";

                    if ($row[29] ?? false || $row[30] ?? false || $row[31] ?? false) {
                        $extra .= 'Your social accounts are listed as: ' . implode(", ",
                                array_filter([
                                    ($row[29] ?? false) ? githublink($row[29]) : '',
                                    ($row[30] ?? false) ? justlink($row[30]) : '',
                                    ($row[31] ?? false) ? twitterlink($row[31]) : '',
                                ]));
                        $extra .= '. ';
                    }
                    if ($row[27]) {
                        $extra .= 'Your location is saved as ' . $row[27] . '. ';
                    }

                    if ($row[2] ?? false && $row[3] ?? false && $row[5] ?? false && $row[6]) {
                        $position2 = $row[5] . ', ' . $row[6];
                        $positions = explode(',', $position2);
                        $positions = trimrow($positions);
                        $position  = $row[2] . ', ' . implode(', ', $positions);
                        $extra     .= 'Position: ' . $position . ' at ' . $row[2] . '. ';
                    }

                    $extra .= 'Additionally, 21.co (Earn.com) tagged you as: ';
                    foreach (
                        [
                            '33' => 'vc',
                            '34' => 'angel',
                            '35' => 'investor/trader',
                            '36' => 'altcoin investor',
                            '37' => 'executive',
                            '38' => 'media/journalist',
                            '39' => 'blockchain dev/engineer',
                            '40' => 'crypto CEO / executive',
                            '41' => 'banker',
                            '42' => 'consultant',
                            '43' => 'other tech services',
                            '46' => 'software engineer',
                            '47' => 'marketing / sales / account exec',
                            '44' => 'a cryptocurrency or exchange',
                        ] as $k => $v
                    ) {
                        if ($row[(int)$k] == 'x') {
                            $extra .= $v . ', ';
                        }
                    }

                    $extra = trim($extra, ', ');

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[0],
                        'extra' => $extra,
                    ]);

                }
            }
        }

    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-inv']) {
    try {
        $sheet = $spreadsheet->getSheet(5);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '1'  => 'price',
            '14' => 'name',

            '2' => 'github',
            '3' => 'linkedin',
            '4' => 'twitter',
            '5' => 'facebook',
            '6' => 'youtube',


            '33' => 'vc',
            '34' => 'angel',
            '35' => 'investor/trader',
            '36' => 'altcoin investor',
            '37' => 'executive',
            '38' => 'media/journalist',
            '39' => 'blockchain dev/engineer',
            '40' => 'crypto CEO / executive',
            '41' => 'banker',
            '42' => 'consultant',
            '43' => 'other tech services',
            '46' => 'software engineer',
            '47' => 'marketing / sales / account exec',
            '44' => 'a cryptocurrency or exchange',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, 'inv-21co', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[26];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra  = '';
                    $row[1] = ! empty($row[1]) ?: "0";
                    $extra  .= "The default contact price for your " . (($row[14] == 'vip') ? 'VIP ' : '') . "account {$row[11]} is listed as $" . $row[1] . ". ";

                    if ($row[2] ?? false || $row[3] ?? false || $row[4] ?? false || $row[5] ?? false || $row[6] ?? false) {
                        $extra .= 'Your social accounts are listed as: ' . implode(", ",
                                array_filter([
                                    ($row[2] ?? false) ? githublink($row[2]) : '',
                                    ($row[3] ?? false) ? justlink($row[3]) : '',
                                    ($row[4] ?? false) ? twitterlink($row[4]) : '',
                                    ($row[5] ?? false) ? justlink($row[5]) : '',
                                    ($row[6] ?? false) ? justlink($row[6]) : '',
                                ]));
                        $extra .= '. ';
                    }

                    if ($row[13] ?? false) {
                        $extra .= 'Position: ' . $row[13];
                        if ($row[12] ?? false) {
                            $extra .= 'at' . $row[12];
                        }
                        $extra .= '. ';
                    } else {
                        if ($row[12] ?? false) {
                            $extra .= 'Affiliated with' . $row[12] . '. ';
                        }
                    }

                    $extra .= 'Additionally, 21.co (Earn.com) tagged you as: ' . $row[18];

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[14],
                        'extra' => $extra,
                    ]);

                }
            }
        }

    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-angel']) {
    try {
        $sheet = $spreadsheet->getSheet(6);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '2'  => 'email',
            '6'  => 'name',
            '9'  => 'tag',
            '10' => 'location',

            '12' => 'linkedin',
            '13' => 'twitter',
            '14' => 'facebook',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '20k-angel', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[2];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra = '';
                    $extra .= "You are categorized under " . (empty($row[9]) ? 'no category' : $row[9]) . ". ";

                    if ($row[12] ?? false || $row[13] ?? false || $row[14] ?? false) {
                        $extra .= 'Your social accounts are listed as: ' . implode(", ",
                                array_filter([
                                    ($row[12] ?? false) ? justlink($row[12]): '',
                                    ($row[13] ?? false) ? twitterlink($row[13]): '',
                                    ($row[14] ?? false) ? justlink($row[14]): '',
                                ]));
                        $extra .= '. ';
                    }

                    if ($row[10] ?? false) {
                        $extra .= 'You are listed as being from ' . $row[10] . '. ';
                    }

                    $extra .= 'Additionally, your education and work history are also in the list but not presented here. If you would like to find out exactly which other information was sold, email us from the address you checked with.';

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[6],
                        'extra' => $extra,
                    ]);

                }
            }
        }

    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-asian']) {
    try {
        $sheet = $spreadsheet->getSheet(7);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '0' => 'email',

            '2' => 'name',

            '3' => 'locale',
            '4' => 'home',

            '1' => 'meetup',
            '6' => 'linkedin',
            '7' => 'twitter',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '20k-asian', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[0];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra = '';
                    if ($row[9] ?? false) {
                        $extra .= "Alternative name: " . (empty($row[9]) ? 'none' : $row[9]);
                        if ($row[21] ?? false) {
                            $extra .= ' / ' . $row[21];
                        }
                        $extra .= ". ";
                    }


                    if ($row[1] ?? false || $row[6] ?? false || $row[7] ?? false) {
                        $extra .= 'Your social accounts are listed as: ' . implode(", ",
                                array_filter([
                                    ($row[1] ?? false) ? justlink($row[1]): '',
                                    ($row[6] ?? false) ? justlink($row[6]): '',
                                    ($row[7] ?? false) ? twitterlink($row[7]): '',
                                ]));
                        $extra .= '. ';
                    }

                    if ($row[3] ?? false) {
                        $extra .= 'You are listed as based in ' . $row[10];
                        if ($row[4] ?? false) {
                            $extra .= ', originally from ' . $row[4];
                        }
                        $extra .= '. ';
                    }

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[2],
                        'extra' => $extra,
                    ]);

                }
            }
        }

    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['20k'] && $ifs['20k-cryptw']) {
    try {
        $sheet = $spreadsheet->getSheet(8);
        $sheet = $sheet->toArray();

        array_pop($sheet);
        $definition = [
            '1'  => 'twitter',
            '2'  => 'name',
            '9'  => 'source',
            '10' => 'email',
        ];

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, '20k-cryptw', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[10];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra = 'You (<a href="https://twitter.com/' . $row[1] . '">@' . $row[1] . '</a>) were sourced from ' . (! empty($row[9]) ? '<a href="' . $row[9] . '">' . $row[9] . '</a>' : 'no list in particular.');

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[2],
                        'extra' => $extra,
                    ]);

                }
            }
        }

    } catch (\Throwable $e) {
        die('Error while importing: '. $e->getMessage());
    }
}

if ($ifs['p2p']) {
    try {
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($files[1]);
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files[1]);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $sheet->toArray();

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, 'p2pevent17', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        array_pop($sheet);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[6];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra = $row[2] . ' at ' . $row[3] . ' from ' . $row[4] . ', ' . $row[5];

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[0] . ' ' . $row[1],
                        'extra' => $extra,
                    ]);

                }
            }
        }


    } catch (\Throwable $e) {
        die($e->getMessage());
    }

}

if ($ifs['con17']) {
    try {
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($files[2]);
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files[2]);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $sheet->toArray();

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, 'con17', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        array_pop($sheet);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[1];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra =
                        (! empty($row[4]) ? $row[4] : "Unknown position")
                        . ' at '
                        . (! empty($row[5]) ? $row[5] : "unknown company");

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[0],
                        'extra' => $extra,
                    ]);

                }
            }
        }


    } catch (\Throwable $e) {
        die($e->getMessage());
    }

}

if ($ifs['lendit']) {
    try {
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($files[3]);
        /** @var Xlsx $reader */
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files[3]);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $sheet->toArray();

        $query     = "INSERT INTO emails (`email`, `list_id`, `notified`, `hash`, `name`, `extra`) VALUES (:email, 'lendit', 0, NULL, :name, :extra) ON DUPLICATE KEY UPDATE `notified` = 0";
        $statement = $pdo->prepare($query);

        array_pop($sheet);

        foreach ($sheet as $i => $row) {

            $row = trimrow($row);

            $emails = $row[5];
            $emails = explode(',', $emails);
            $emails = array_unique(trimrow($emails));

            if (count($emails)) {
                foreach ($emails as $email) {

                    if (empty($email) || ! filter_var($email,
                            FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $extra =
                        (! empty($row[4]) ? $row[4] : "Unknown position")
                        . ' at '
                        . (! empty($row[3]) ? $row[3] : "unknown company") . '. ';

                    if ( ! empty($row[6]) || ! empty($row[7]) || ! empty($row[8]) || ! empty($row[9]) || ! empty($row[10]) || ! empty($row[11])) {
                        $extra .= 'Address: ' .
                                  (! empty($row[6]) ? $row[6] . ", " : "")
                                  . (! empty($row[7]) ? $row[7] . ", " : "")
                                  . (! empty($row[8]) ? $row[8] . ", " : "")
                                  . (! empty($row[9]) ? $row[9] . ", " : "")
                                  . (! empty($row[10]) ? $row[10] . ", " : "")
                                  . (! empty($row[11]) ? $row[11] . ", " : "");

                        $extra = trim($extra, ", ").". ";
                    }

                    $extra .= 'Additionally, you were categorized as ' . $row[12];

                    $statement->execute([
                        'email' => $email,
                        'name'  => $row[2],
                        'extra' => $extra,
                    ]);

                }
            }
        }


    } catch (\Throwable $e) {
        die($e->getMessage());
    }

}

function trimrow($row)
{
    foreach ($row as &$cell) {
        $cell = trim($cell);
    }

    return $row;
}

function twitterlink($handle) {
    if (strpos($handle, 'twitter.com/')) {
        $handle = explode('twitter.com/', $handle);
        $handle = $handle[1];
    }
    $handle = trim($handle, '@');
    return "<a href='https://twitter.com/".$handle."'>@".$handle."</a>";
}

function githublink($username) {
    if (strpos($username, 'github.com/')) {
        $handle = explode('github.com/', $username);
        $handle = $handle[1];
    }
    return "<a href='https://github.com/".$handle."'>github.com/".$handle."</a>";
}

function justlink($handle) {
    return "<a href='".$handle."'>".$handle."</a>";
}