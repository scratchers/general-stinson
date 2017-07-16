<?php

require __DIR__.'/../vendor/autoload.php';

/**
 * Example of using Phirehose to display a live filtered stream using track words
 */
class FilterTrackConsumer extends OauthPhirehose
{
    protected $pdo;
    protected $statement;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->setPdo();

        $this->setStatment();
    }

    public function setStatment(string $sql = null)
    {
        if (is_null($sql)) {
            $sql = 'INSERT INTO tweets (json) VALUES (?)';
        }

        $this->statement = $this->pdo->prepare($sql);
    }

    /**
    * Enqueue each status
    *
    * @param string $status
    */
    public function enqueueStatus($status)
    {
        $this->statement->execute([$status]);
    }

    public function setPdo()
    {
        $hostname = getenv('MYSQL_HOSTNAME');
        $database = getenv('MYSQL_DATABASE');
        $username = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');

        while (empty($this->pdo)) {
            try {
                $pdo = new \PDO("mysql:host=$hostname;
                    charset=UTF8;
                    dbname=$database",
                    $username,
                    $password
                );
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo = $pdo;
            }

            catch (PDOException $e) {
                echo "{$e->getMessage()}\n";
            }

            echo "waiting for database...\n";
            sleep(3);
        }

        return $this->pdo;
    }
}

// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", getenv('TWITTER_CONSUMER_KEY'));
define("TWITTER_CONSUMER_SECRET", getenv('TWITTER_CONSUMER_SECRET'));


// The OAuth data for the twitter account
define("OAUTH_TOKEN", getenv('OAUTH_TOKEN'));
define("OAUTH_SECRET", getenv('OAUTH_SECRET'));

// Start streaming
$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack(require __DIR__.'/../keywords.php');
$sc->setLang('en');
$sc->consume();
