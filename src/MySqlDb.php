<?php

namespace scratchers\salute;

use PDO;
use PDOException;

class MySqlDb implements Tweets
{
    protected $pdo;
    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;

        $this->connectOrKeepTrying();
    }

    public function connectOrKeepTrying()
    {
        $hostname = getenv('MYSQL_HOSTNAME');
        $database = getenv('MYSQL_DATABASE');
        $username = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');

        $this->pdo = null;
        while (empty($this->pdo)) {
            try {
                $pdo = new PDO("mysql:host=$hostname;
                    charset=utf8mb4;
                    dbname=$database",
                    $username,
                    $password
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo = $pdo;
            }

            catch (PDOException $e) {
                echo "{$e->getMessage()}\n";
            }

            echo "waiting for database...\n";
            sleep(2);
        }
    }

    public function getNew(int $limit = null) : array
    {
        return $this->objectify($this->query($limit));
    }

    public function patch(array $tweets)
    {
        $sql = "UPDATE tweets
            SET tweet_id = :tweet_id
                ,tweet = :tweet
                ,salutation = :salutation
            WHERE id = :id
        ";

        $statement = $this->pdo->prepare($sql);

        foreach ($tweets as $tweet) {
            $data = [
                'id' => $tweet->dbid,
                'tweet_id' => $tweet->id_str,
                'tweet' => $tweet->text,
                'salutation' => $tweet->salutation,
            ];

            $statement->execute($data);
        }
    }

    public function delete(array $tweets)
    {
        foreach ($tweets as $tweet) {
            $ids []= $tweet->dbid;
        }

        if (empty($ids)) {
            return;
        }

        $sql = "DELETE FROM tweets WHERE id IN ({$this->getBinders($ids)})";

        $this->pdo->prepare($sql)->execute($ids);
    }

    protected function objectify(array $rows) : array
    {
        $tweets = [];

        foreach ($rows as $index => $row) {
            $tweets[$index] = json_decode($row['json']);
            $tweets[$index]->dbid = $row['id'];
        }

        return $this->filter($tweets);
    }

    protected function filter(array $tweets) : array
    {
        if (is_null($this->filter)) {
            return $tweets;
        }

        $trash = $this->filter->filterTweetsAndReturnTrash($tweets);

        $this->delete($trash);

        return $tweets;
    }

    protected function query($max = null) : array
    {
        $limit = getenv('MYSQL_QUERY_LIMIT') ?: '5000';

        if (is_int($max) && $max > 0) {
            $limit = $max;
        }

        $sql = "SELECT id, json
            FROM tweets
            WHERE tweet_id IS NULL
            ORDER BY id DESC
            LIMIT $limit
        ";

        return $this->pdo->query($sql)->fetchAll();
    }

    protected function getBinders(array $input) : string
    {
		$binders = str_repeat('?', count($input));
		return implode(',', str_split($binders));
	}
}
