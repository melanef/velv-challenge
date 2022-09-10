<?php

namespace App\VOs;

use InvalidArgumentException;

class Ram
{
    public const TYPE_DDR3 = 'DDR3';
    public const TYPE_DDR4 = 'DDR4';

    public const TYPES = [
        self::TYPE_DDR3,
        self::TYPE_DDR4,
    ];

    public LogicSpace $size;

    public string $type;

    /**
     * @param string $raw
     *
     * @return static
     */
    public static function fromString(string $raw): self
    {
        $pattern = sprintf(
            '/^(?<amount>\d+)(?<unit>%s)(?<type>%s)$/i',
            implode('|', LogicSpace::UNITS),
            implode('|', self::TYPES)
        );
        if (!preg_match($pattern, $raw, $matches)) {
            throw new InvalidArgumentException(sprintf('Invalid raw RAM string: "%s"', $raw));
        }

        $ram = new self();
        $ram->size = LogicSpace::fromString(sprintf('%d%s', $matches['amount'], $matches['unit']));
        $ram->type = $matches['type'];

        return $ram;
    }
}
