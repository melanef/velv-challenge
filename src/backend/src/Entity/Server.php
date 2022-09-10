<?php

namespace App\Entity;

use App\VOs\Currency;
use App\VOs\Hdd;
use App\VOs\Ram;

class Server
{
    public string $model;

    public Ram $ram;

    public Hdd $hdd;

    public string $location;

    public Currency $price;
}
