<?php

use App\VO\LogicalSpace;
use PHPUnit\Framework\TestCase;

class LogicalSpaceTest extends TestCase
{
    public function testRejectInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LogicalSpace::fromString('foobar');
    }

    public function testParseValidString(): void
    {
        $logicalSpace = LogicalSpace::fromString('1GB');

        $this->assertEquals(1, $logicalSpace->amount);
        $this->assertEquals('GB', $logicalSpace->unit);
    }

    public function testConvertsToString(): void
    {
        $logicalSpace = new LogicalSpace();
        $logicalSpace->amount = 5;
        $logicalSpace->unit = 'TB';

        $this->assertEquals('5TB', $logicalSpace->asString());
    }

    public function testNormalizes(): void
    {
        $logicalSpace = new LogicalSpace();
        $logicalSpace->amount = 5;
        $logicalSpace->unit = 'TB';

        $this->assertEquals(1024 * 5, $logicalSpace->normalize());
    }

    public function testCompares(): void
    {
        $reference = new LogicalSpace();
        $reference->amount = 5;
        $reference->unit = 'TB';

        $equal = new LogicalSpace();
        $equal->amount = 5120;
        $equal->unit = 'GB';

        $greater = new LogicalSpace();
        $greater->amount = 10;
        $greater->unit = 'TB';

        $lesser = new LogicalSpace();
        $lesser->amount = 1;
        $lesser->unit = 'TB';

        $this->assertTrue($reference->isGreaterThanOrEqual($equal));
        $this->assertTrue($reference->isLesserThanOrEqual($equal));

        $this->assertTrue($reference->isGreaterThanOrEqual($lesser));
        $this->assertTrue($reference->isGreaterThan($lesser));

        $this->assertTrue($reference->isLesserThanOrEqual($greater));
        $this->assertTrue($reference->isLesserThan($greater));
    }
}
