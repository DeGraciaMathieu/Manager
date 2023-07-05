<?php

namespace Tests\Stubs\Drivers;

use Tests\Stubs\Contracts\Driver;

class FooDriver implements Driver
{
    public function doAnything(): string
    {
        return 'i do anything from the foo driver';
    }
}
