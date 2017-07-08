<?php

namespace scratchers\salute;

class Parser
{
    /**
     * @return mixed
     *   substring salutation from statement or FALSE on failure
     */
    public function parse(string $statement)
    {
        $regex = '/(major|private|general|kernel) [\w\-]+/';
        $matches = [];

        if (false == preg_match($regex, $statement, $matches)) {
            return false;
        }

        return $matches[0];
    }
}
