<?php

namespace Tests\Stubs;

use Tests\Stubs\Drivers\BarDriver;
use Tests\Stubs\Drivers\FooDriver;
use Tests\Stubs\Contracts\Driver;
use DeGraciaMathieu\Manager\Manager;

class StubManager extends Manager {

    public function createBarDriver(): Driver
    {
        return new BarDriver();
    }

    public function createFooDriver(): Driver
    {
        return new FooDriver();
    }

    public function getDefaultDriver(): string
    {
        return 'bar';
    }
}