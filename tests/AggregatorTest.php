<?php

namespace DeGraciaMathieu\Manager\Tests;

use PHPUnit\Framework\TestCase;
use DeGraciaMathieu\Manager\Aggregator;
use DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException;

class AggregatorTest extends TestCase
{
    /**
     * @test
     */
    public function classic_uses_cases()
    {
        $aggregator = new Aggregator();

        $aggregator->set('name_1', 'driver_1');
        $aggregator->set('name_2', 'driver_2');

        $drivers = $aggregator->all();

        $this->assertEquals($drivers, [
            'name_1' => 'driver_1',
            'name_2' => 'driver_2',
        ]);

        $driver = $aggregator->get('name_1');

        $this->assertEquals($driver, 'driver_1');
        $this->assertTrue($aggregator->has('name_1'));
        $this->assertFalse($aggregator->has('unexpected_driver_name'));
    }

    /**
     * @test
     */
    public function driver_already_registered()
    {
        $aggregator = new Aggregator();

        $this->expectException(DriverOverwrittenException::class);

        $aggregator->set('name_1', 'driver_1');
        $aggregator->set('name_1', 'driver_2');
    }
}
