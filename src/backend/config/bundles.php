<?php

use Nelmio\CorsBundle\NelmioCorsBundle;

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    NelmioCorsBundle::class => ['all' => true],
];
