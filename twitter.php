<?php

require __DIR__.'/vendor/autoload.php';

/**
 * Example of using Phirehose to display a live filtered stream using track words
 */
class FilterTrackConsumer extends OauthPhirehose
{
      protected $statement;

      /**
       * Enqueue each status
       *
       * @param string $status
       */
      public function enqueueStatus($status)
      {
          if (is_null($this->statement)) {
              // returns an instance of PDO
              // https://github.com/jpuck/qdbp
              $pdo = require __DIR__.'/pdo.php';

              $sql = 'INSERT INTO tweets (json) VALUES (?)';

              $this->statement = $pdo->prepare($sql);
          }

          $this->statement->execute([$status]);
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
$sc->setTrack(array('major', 'general', 'kernel'));
$sc->consume();
