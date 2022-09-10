<?php

namespace App\Repository;

use App\Entity\Server;
use App\Param\ServerFilterParams;

interface ServerRepository
{
    /**
     * @param ServerFilterParams $params
     *
     * @return Server[]
     */
    public function fetch(ServerFilterParams $params): array;
}
