<?php

use App\VO\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testRejectInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Currency::fromString('foobar');
    }

    public function testParseValidString(): void
    {
        $currency = Currency::fromString('$100.00');

        $this->assertEquals(10000, $currency->value);
        $this->assertEquals('$', $currency->symbol);
        $this->assertEquals(2, $currency->decimalPlaces);
    }

    public function testConvertsToString(): void
    {
        $currency = new Currency();
        $currency->decimalPlaces = 2;
        $currency->symbol = '$';
        $currency->value = 5000;

        $this->assertEquals('$50.00', $currency->asString());
    }
}
