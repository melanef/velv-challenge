<?php

namespace App\VOs;

use InvalidArgumentException;

class Hdd
{
    public const TYPE_SATA2 = 'SATA2';
    public const TYPE_SSD = 'SSD';
    public const TYPE_SAS = 'SAS';

    public const TYPES = [
        self::TYPE_SATA2,
        self::TYPE_SSD,
        self::TYPE_SAS,
    ];

    public int $disks;

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
            '/^(?<disks>\d+)x(?<amount>\d+)(?<unit>%s)(?<type>%s)$/i',
            implode('|', LogicSpace::UNITS),
            implode('|', self::TYPES)
        );
        if (!preg_match($pattern, $raw, $matches)) {
            throw new InvalidArgumentException(sprintf('Invalid raw RAM string: "%s"', $raw));
        }

        $hdd = new self();
        $hdd->disks = $matches['disks'];
        $hdd->size = LogicSpace::fromString(sprintf('%d%s', $matches['amount'], $matches['unit']));
        $hdd->type = $matches['type'];

        return $hdd;
    }

    public function isGreaterAndEqualThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isGreaterAndEqualThan(LogicSpace::fromString($rawSize));
    }

    public function isGreaterThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isGreaterThan(LogicSpace::fromString($rawSize));
    }

    public function isLesserAndEqualThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isLesserAndEqualThan(LogicSpace::fromString($rawSize));
    }

    public function isLesserThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isLesserThan(LogicSpace::fromString($rawSize));
    }

    private function getComputedSize(): LogicSpace
    {
        $computedSize = clone $this->size;
        $computedSize->amount = $this->disks * $this->size->amount;

        return $computedSize;
    }
}
