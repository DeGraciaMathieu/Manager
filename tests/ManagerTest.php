<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Stubs\StubManager;
use Tests\Stubs\Drivers\BarDriver;
use Tests\Stubs\Drivers\FooDriver;
use DeGraciaMathieu\Manager\Exceptions\DriverResolutionException;

class ManagerTests extends TestCase
{
    /**
     * @test
     */
    public function it_can_instantiate_driver(): void
    {
        $manager = new StubManager();

        $driver = $manager->driver('foo');

        $this->assertInstanceOf(FooDriver::class, $driver);
    }

    /**
     * @test
     */
    public function it_can_instantiate_default_driver(): void
    {
        $manager = new StubManager();

        $driver = $manager->driver();

        $this->assertInstanceOf(BarDriver::class, $driver);
    }

    /**
     * @test
     */
    public function it_can_directly_call_a_method_of_a_driver(): void
    {
        $manager = new StubManager();

        $doAnything = $manager->driver()->doAnything();

        $this->assertSame(
            'i do anything from the bar driver',
            $doAnything
        );
    }

    /**
     * @test
     */
    public function it_returns_a_new_driver_instance_by_call(): void
    {
        $manager = new StubManager(singleton: false);

        $driver = $manager->driver();
        $anotherDriver = $manager->driver();

        $this->assertNotSame($driver, $anotherDriver);
    }

    /**
     * @test
     */
    public function it_able_to_give_the_same_driver_instance(): void
    {
        $manager = new StubManager(singleton: true);

        $driver = $manager->driver();
        $anotherDriver = $manager->driver();

        $this->assertSame($driver, $anotherDriver);
    }

    /**
     * @test
     */
    public function it_returns_an_exception_if_the_desired_driver_does_not_exist(): void
    {
        $manager = new StubManager();

        $this->expectException(DriverResolutionException::class);

        $manager->driver('qsd');
    }
}
