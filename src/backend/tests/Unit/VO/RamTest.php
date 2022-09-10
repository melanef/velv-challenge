<?php

use App\VO\Ram;
use PHPUnit\Framework\TestCase;

class RamTest extends TestCase
{
    public function testRejectInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Ram::fromString('foobar');
    }

    public function testParseValidString(): void
    {
        $ram = Ram::fromString('1GBDDR3');

        $this->assertEquals(1, $ram->size->amount);
        $this->assertEquals('GB', $ram->size->unit);
        $this->assertEquals('DDR3', $ram->type);
    }
}
