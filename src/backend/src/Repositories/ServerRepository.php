<?php

namespace App\Repositories;

use App\Entity\Server;
use App\Params\ServerFilterParams;

interface ServerRepository
{
    /**
     * @param ServerFilterParams $params
     *
     * @return Server[]
     */
    public function fetch(ServerFilterParams $params): array;
}
