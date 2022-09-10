<?php

namespace App\VO;

use InvalidArgumentException;

class LogicalSpace
{
    public const UNIT_GB = 'GB';
    public const UNIT_TB = 'TB';

    public const UNITS = [
        self::UNIT_GB,
        self::UNIT_TB,
    ];

    private const UNIT_FACTOR = [
        self::UNIT_GB => 1,
        self::UNIT_TB => 1024,
    ];

    public int $amount;

    public string $unit;

    /**
     * @param string $raw
     *
     * @return static
     */
    public static function fromString(string $raw): self
    {
        $pattern = sprintf('/^(?<amount>\d+)(?<unit>%s)$/i', implode('|', self::UNITS));
        if (!preg_match($pattern, $raw, $matches)) {
            throw new InvalidArgumentException(sprintf('Invalid raw logic space string: "%s"', $raw));
        }

        $logicSpace = new self();
        $logicSpace->amount = $matches['amount'];
        $logicSpace->unit = $matches['unit'];

        return $logicSpace;
    }

    public function asString(): string
    {
        return sprintf('%d%s', $this->amount, $this->unit);
    }

    public function isGreaterThanOrEqual(LogicalSpace $other): bool
    {
        return $this->normalize() >= $other->normalize();
    }

    public function isGreaterThan(LogicalSpace $other): bool
    {
        return $this->normalize() > $other->normalize();
    }

    public function isLesserThanOrEqual(LogicalSpace $other): bool
    {
        return $this->normalize() <= $other->normalize();
    }

    public function isLesserThan(LogicalSpace $other): bool
    {
        return $this->normalize() < $other->normalize();
    }

    public function normalize(): int
    {
        return $this->amount * self::UNIT_FACTOR[$this->unit];
    }
}
