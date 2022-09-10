<?php

namespace App\Repositories;

use App\Entity\Server;
use App\Params\ServerFilterParams;
use App\VOs\Currency;
use App\VOs\Hdd;
use App\VOs\Ram;
use RuntimeException;
use Symfony\Component\Filesystem\Path;

class CsvServerRepository implements ServerRepository
{
    private bool $isLoaded = false;

    /**
     * @var Server[]
     */
    private array $servers;

    private string $location;

    /**
     * @param string     $location
     */
    public function __construct(string $location)
    {
        $this->location = $location;
    }

    /**
     * @inheritDoc
     */
    public function fetch(ServerFilterParams $params): array
    {
        $this->load();

        $result = [];
        foreach ($this->servers as $server) {
            if ($params->location && $params->location !== $server->location) {
                continue;
            }

            if ($params->hddType && $params->hddType !== $server->hdd->type) {
                continue;
            }

            if ($params->hddMin && $server->hdd->isLesserThan($params->hddMin)) {
                continue;
            }

            if ($params->hddMax && $server->hdd->isGreaterThan($params->hddMax)) {
                continue;
            }

            if ($params->ramOptions && in_array($server->ram->size->asString(), $params->ramOptions)) {
                continue;
            }

            $result[] = $server;
        }

        return $result;
    }

    private function load(): void
    {
        if ($this->isLoaded) {
            return;
        }

        if (($fp = fopen(Path::makeAbsolute($this->location, '/var/www/html/'), 'r')) === FALSE) {
            throw new RuntimeException(sprintf('Couldn\'t open CSV Repository on file "%s"', $this->location));
        }

        while (($row = fgetcsv($fp, 1000, ",")) !== FALSE) {
            $server = new Server();
            $server->model = $row[0];
            $server->ram = Ram::fromString($row[1]);
            $server->hdd = Hdd::fromString($row[2]);
            $server->location = $row[3];
            $server->price = Currency::fromString($row['4']);

            $this->servers[] = $server;
        }

        fclose($fp);

        $this->isLoaded = true;
    }
}
