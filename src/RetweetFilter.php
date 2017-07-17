<?php

namespace scratchers\salute;

class RetweetFilter implements Filter
{
    public function filterTweetsAndReturnTrash(array &$tweets) : array
    {
        $trash = [];

        foreach ($tweets as $index => $tweet) {
            if (isset($tweet->retweeted_status)) {
                $trash []= $tweet;
                unset($tweets[$index]);
            }
        }

        return $trash;
    }
}
