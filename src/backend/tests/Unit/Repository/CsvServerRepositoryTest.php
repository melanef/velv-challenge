<?php

use App\Param\ServerFilterParams;
use App\Repository\CsvServerRepository;
use App\VO\LogicalSpace;
use PHPUnit\Framework\TestCase;

class CsvServerRepositoryTest extends TestCase
{
    protected static CsvServerRepository $repository;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$repository = new CsvServerRepository('tests/resources/servers.csv');
    }

    public function testFailOnInvalidFileLocation(): void
    {
        $this->expectException(RuntimeException::class);

        $repository = new CsvServerRepository('foobar');
        $repository->fetch(new ServerFilterParams());
    }

    public function testFetchAllWithNoParams(): void
    {
        $this->assertCount(11, self::$repository->fetch(new ServerFilterParams()));
    }

    public function testFetchByLocation(): void
    {
        $params = new ServerFilterParams();
        $params->location = 'Washington D.C.WDC-01';

        $fetched = self::$repository->fetch($params);
        $this->assertCount(1, $fetched);
        $this->assertEquals($params->location, $fetched[0]->location);
    }

    public function testFetchByHddType(): void
    {
        $params = new ServerFilterParams();
        $params->hddType = 'SSD';

        $fetched = self::$repository->fetch($params);
        $this->assertCount(2, $fetched);
        foreach ($fetched as $server) {
            $this->assertEquals($params->hddType, $server->hdd->type);
        }
    }

    public function testFetchByHddSize(): void
    {
        $params = new ServerFilterParams();
        $params->hddMin = '3TB';
        $params->hddMax = '10TB';

        $fetched = self::$repository->fetch($params);
        $this->assertCount(4, $fetched);
        foreach ($fetched as $server) {
            $this->assertTrue($server->hdd->isGreaterThanOrEqual($params->hddMin));
            $this->assertTrue($server->hdd->isLesserThanOrEqual($params->hddMax));
        }
    }

    public function testFetchByRamOption(): void
    {
        $params = new ServerFilterParams();
        $params->ramOptions = [
            '16GB',
            '32GB',
        ];

        $fetched = self::$repository->fetch($params);
        $this->assertCount(6, $fetched);
        foreach ($fetched as $server) {
            $this->assertContains($server->ram->size->asString(), $params->ramOptions);
        }
    }
}
