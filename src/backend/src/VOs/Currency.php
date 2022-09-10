<?php

namespace App\VOs;

use InvalidArgumentException;

class Currency
{
    public string $symbol;

    public int $value;

    public int $decimalPlaces;

    public static function fromString(string $raw): self
    {
        if (!preg_match('/^(?<symbol>[^\d]+)(?<unit>\d+)\.(?<fraction>\d+)$/i', $raw, $matches)) {
            throw new InvalidArgumentException(sprintf('Invalid raw RAM string: "%s"', $raw));
        }

        $currency = new self();
        $currency->symbol = $matches['symbol'];
        $currency->decimalPlaces = strlen($matches['fraction']);
        $currency->value = ($matches['unit'] * (10 ** $currency->decimalPlaces)) + $matches['fraction'];

        return $currency;
    }
}
