<?php

require __DIR__.'/../src/Parser.php';

use scratchers\salute\Parser;

$hostname = getenv('MYSQL_HOSTNAME');
$database = getenv('MYSQL_DATABASE');
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');

$hostname = '172.22.22.2';
$database = 'salute';
$username = 'saluter';
$password = 'password';

while (empty($pdo)) {
    try {
        $pdo = new \PDO("mysql:host=$hostname;
            charset=UTF8;
            dbname=$database",
            $username,
            $password
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    catch (PDOException $e) {
        echo "{$e->getMessage()}\n";
    }

    echo "waiting for database...\n";
    sleep(1);
}

$sql = "SELECT * FROM tweets WHERE rejected IS NULL ORDER BY id DESC";

$parser = new Parser;

while (!empty($rows = $pdo->query($sql)->fetchAll())) {
    $count = 1;
    foreach ($rows as $row) {
        $tweet = json_decode($row['json']);
        $tweets[$row['id']]['tweet'] = $tweet->text;
        $tweets[$row['id']]['tweet_id'] = $tweet->id_str;


        if (false === ($salutation = $parser->parse($tweet->text))) {
            $tweets[$row['id']]['rejected'] = 1;
        } else {
            $tweets[$row['id']]['rejected'] = 0;
            $tweets[$row['id']]['salutation'] = $salutation;
        }

        if (!empty($salutation)) {
            $count++;
            echo "$row[id]: {$tweet->text}\n";
            echo "$row[id]: $salutation\n";
        }
    }
    die("done: $count salutations made.\n");
}
