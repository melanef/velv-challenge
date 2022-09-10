<?php

use App\VO\Hdd;
use App\VO\LogicalSpace;
use PHPUnit\Framework\TestCase;

class HddTest extends TestCase
{
    public function testRejectInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Hdd::fromString('foobar');
    }

    public function testParseValidString(): void
    {
        $hdd = Hdd::fromString('1x1GBSATA2');

        $this->assertEquals(1, $hdd->disks);
        $this->assertEquals(1, $hdd->size->amount);
        $this->assertEquals('GB', $hdd->size->unit);
        $this->assertEquals('SATA2', $hdd->type);
    }

    public function testCompares(): void
    {
        $reference = new Hdd();
        $reference->size = new LogicalSpace();
        $reference->size->amount = 5;
        $reference->size->unit = 'TB';
        $reference->disks = 1;
        $reference->type = Hdd::TYPE_SSD;

        $equal = new Hdd();
        $equal->size = new LogicalSpace();
        $equal->size->amount = 1;
        $equal->size->unit = 'TB';
        $equal->disks = 5;
        $equal->type = Hdd::TYPE_SSD;

        $greater = new Hdd();
        $greater->size = new LogicalSpace();
        $greater->size->amount = 10;
        $greater->size->unit = 'TB';
        $greater->disks = 2;
        $greater->type = Hdd::TYPE_SSD;

        $lesser = new Hdd();
        $lesser->size = new LogicalSpace();
        $lesser->size->amount = 100;
        $lesser->size->unit = 'GB';
        $lesser->disks = 5;
        $lesser->type = Hdd::TYPE_SSD;

        $this->assertTrue($reference->isGreaterThanOrEqual($equal->getSizeString()));
        $this->assertTrue($reference->isLesserThanOrEqual($equal->getSizeString()));

        $this->assertTrue($reference->isGreaterThanOrEqual($lesser->getSizeString()));
        $this->assertTrue($reference->isGreaterThan($lesser->getSizeString()));

        $this->assertTrue($reference->isLesserThanOrEqual($greater->getSizeString()));
        $this->assertTrue($reference->isLesserThan($greater->getSizeString()));
    }
}
