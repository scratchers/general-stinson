<?php

use PHPUnit\Framework\TestCase;
use scratchers\salute\Parser;

class ParserTest extends TestCase
{
    public function test_instantiates_object()
    {
        $this->assertInstanceOf(Parser::class, new Parser);
    }

    public function saluteDataProvider()
    {
        return [
            [
                "This is gonna be a major cleanup.",
                "major cleanup",
            ],
            [
                "Only women with major baggage make porn.",
                "major baggage",
            ],
            [
                "I've got a major craving for a mojito.",
                "major craving",
            ],
            [
                "It's been a major pleasure.",
                "major pleasure",
            ],
            [
                "It's really a major buzz-kill.",
                "major buzz-kill",
            ],
            [
                "Look, it's a private thing between me and Ted.",
                "private thing",
            ],
            [
                "Oh, God, we're back to your stupid little private joke again?",
                "private joke",
            ],
            [
                "Sorry everything is in general disarray.",
                "general disarray",
            ],
            [
                "It's general knowledge.",
                "general knowledge",
            ],
            [
                "I've got a kernel stuck in my teeth.",
                "kernel stuck",
            ],
            [
                "In general, I think it's a bad idea.",
                false,
            ],
            [
                "What do you think about it in general?",
                false,
            ],
            [
                "The question is who poisoned the GENERAL at Bomas & was he driven to the hospital or taken from his House?",
                false,
            ],
            [
                "Still struggling to understand the death of General Joseph Nkaissery.",
                false,
            ],
            [
                "Mosul victory announcement 'imminent', US Brigadier General Sofge tells me from Baghdad",
                false,
            ],
            [
                "He's STILL a celebrated general....just not in DC, Hollywood or the elite media.",
                false,
            ],
            [
                "Mosul victory announcement 'imminent': US general - Yahoo Singapore News",
                false,
            ],
        ];
    }

    /**
     * @dataProvider saluteDataProvider
     */
    public function test_returns_salutation($statement, $expected)
    {
        $actual = (new Parser)->parse($statement);

        $this->assertSame($expected, $actual);
    }
}
