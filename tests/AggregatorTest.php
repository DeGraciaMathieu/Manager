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
    public function it_able_to_find_a_driver(): void
    {
        $aggregator = new Aggregator();

        $aggregator->set('name_1', 'driver_1');

        $driver = $aggregator->get('name_1');

        $this->assertEquals($driver, 'driver_1');
    }

    /**
     * @test
     */
    public function it_able_to_get_all_drivers(): void
    {
        $aggregator = new Aggregator();

        $aggregator->set('name_1', 'driver_1');
        $aggregator->set('name_2', 'driver_2');

        $drivers = $aggregator->all();

        $this->assertEquals($drivers, [
            'name_1' => 'driver_1',
            'name_2' => 'driver_2',
        ]);
    }

    /**
     * @test
     */
    public function it_able_to_determine_if_a_driver_exists(): void
    {
        $aggregator = new Aggregator();

        $aggregator->set('name_1', 'driver_1');

        $this->assertTrue($aggregator->has('name_1'));
        $this->assertFalse($aggregator->has('name_2'));
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_you_overwrite_a_driver()
    {
        $aggregator = new Aggregator();

        $this->expectException(DriverOverwrittenException::class);

        $aggregator->set('name_1', 'driver_1');
        $aggregator->set('name_1', 'driver_2');
    }
}
