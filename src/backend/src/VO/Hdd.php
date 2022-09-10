<?php

namespace App\VO;

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

    public LogicalSpace $size;

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
            implode('|', LogicalSpace::UNITS),
            implode('|', self::TYPES)
        );
        if (!preg_match($pattern, $raw, $matches)) {
            throw new InvalidArgumentException(sprintf('Invalid raw HDD string: "%s"', $raw));
        }

        $hdd = new self();
        $hdd->disks = $matches['disks'];
        $hdd->size = LogicalSpace::fromString(sprintf('%d%s', $matches['amount'], $matches['unit']));
        $hdd->type = $matches['type'];

        return $hdd;
    }

    public function getSizeString(): string
    {
        return $this->getComputedSize()->asString();
    }

    public function isGreaterThanOrEqual(string $rawSize): bool
    {
        return $this->getComputedSize()->isGreaterThanOrEqual(LogicalSpace::fromString($rawSize));
    }

    public function isGreaterThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isGreaterThan(LogicalSpace::fromString($rawSize));
    }

    public function isLesserThanOrEqual(string $rawSize): bool
    {
        return $this->getComputedSize()->isLesserThanOrEqual(LogicalSpace::fromString($rawSize));
    }

    public function isLesserThan(string $rawSize): bool
    {
        return $this->getComputedSize()->isLesserThan(LogicalSpace::fromString($rawSize));
    }

    private function getComputedSize(): LogicalSpace
    {
        $computedSize = clone $this->size;
        $computedSize->amount = $this->disks * $this->size->amount;

        return $computedSize;
    }
}
