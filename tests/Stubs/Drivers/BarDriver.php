<?php

namespace Tests\Stubs\Drivers;

use Tests\Stubs\Contracts\Driver;

class BarDriver implements Driver
{
    public function doAnything(): string
    {
        return 'i do anything from the bar driver';
    }
}
