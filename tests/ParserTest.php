<?php

use PHPUnit\Framework\TestCase;
use scratchers\salute\Parser;

class ParserTest extends TestCase
{
    public function test_instantiates_object()
    {
        $this->assertInstanceOf(Parser::class, new Parser);
    }
}
