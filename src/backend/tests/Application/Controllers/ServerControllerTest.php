<?php

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServerControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testFetchWithoutParams(): void
    {
        $this->client->request('GET', '/servers');
        static::assertResponseIsSuccessful();
    }

    /**
     * @throws JsonException
     */
    public function testFilterServersByHddSize(): void
    {
        $this->client->request(
            'GET',
            '/servers',
            [
                'hdd_min' => '3TB',
                'hdd_max' => '10TB',
            ]
        );
        static::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($responseData as $server) {
            $hddSize = $server['hdd']['disks'] * $server['hdd']['size']['amount'] * ($server['hdd']['size']['unit'] === 'TB' ? 1024 : 1);
            $this->assertGreaterThanOrEqual(3 * 1024, $hddSize);
            $this->assertLessThanOrEqual(10 * 1024, $hddSize);
        }
    }

    /**
     * @throws JsonException
     */
    public function testFilterServersByRamOptions(): void
    {
        $this->client->request(
            'GET',
            '/servers',
            [
                'ram_options' => ['16GB', '32GB',],
            ],
        );
        static::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($responseData as $server) {
            $ramSize = $server['ram']['size']['amount'] * ($server['ram']['size']['unit'] === 'TB' ? 1024 : 1);
            $this->assertContains($ramSize, [16, 32]);
        }
    }

    /**
     * @throws JsonException
     */
    public function testFilterServersByHddType(): void
    {
        $this->client->request(
            'GET',
            '/servers',
            [
                'hdd_type' => 'SAS',
            ],
        );
        static::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($responseData as $server) {
            $this->assertEquals('SAS', $server['hdd']['type']);
        }
    }

    /**
     * @throws JsonException
     */
    public function testFilterServersByLocation(): void
    {
        $this->client->request(
            'GET',
            '/servers',
            [
                'location' => 'FrankfurtFRA-10',
            ],
        );
        static::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($responseData as $server) {
            $this->assertEquals('FrankfurtFRA-10', $server['location']);
        }
    }
}
