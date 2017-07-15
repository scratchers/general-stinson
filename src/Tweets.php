<?php

namespace scratchers\salute;

interface Tweets
{
    public function getNew(int $limit = null) : array;
    public function patch(array $tweets);
    public function delete(array $tweets);
}
