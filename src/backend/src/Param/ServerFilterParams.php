<?php

namespace App\Param;

class ServerFilterParams
{
    public ?string $hddMin = null;

    public ?string $hddMax = null;

    /** @var string[] */
    public ?array $ramOptions = null;

    public ?string $hddType = null;

    public ?string $location = null;
}
