<?php

require __DIR__.'/../vendor/autoload.php';

use scratchers\salute\MySqlDb;
use scratchers\salute\Parser;
use scratchers\salute\RetweetFilter;

$tweets = new MySqlDb(new RetweetFilter);

$parser = new Parser;

while (!empty($batch = $tweets->getNew())) {
    $trash = [];
    $patch = [];

    foreach ($batch as $tweet) {
        if (false === ($salutation = $parser->parse($tweet->text))) {
            $trash []= $tweet;
        } else {
            $tweet->salutation = $salutation;
            $patch []= $tweet;
        }
    }

    $tweets->delete($trash);
    $tweets->patch($patch);
}
