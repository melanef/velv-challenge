<?php

namespace App\Entity;

use App\VO\Currency;
use App\VO\Hdd;
use App\VO\Ram;

class Server
{
    public string $model;

    public Ram $ram;

    public Hdd $hdd;

    public string $location;

    public Currency $price;
}
