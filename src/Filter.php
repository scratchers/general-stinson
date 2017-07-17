<?php

namespace scratchers\salute;

interface Filter
{
    public function filterTweetsAndReturnTrash(array &$tweets) : array;
}
