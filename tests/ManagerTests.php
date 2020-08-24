<?php

namespace DeGraciaMathieu\Manager\Tests;

use PHPUnit\Framework\TestCase;
use DeGraciaMathieu\Manager\Manager;
use DeGraciaMathieu\Manager\Exceptions\DriverResolutionException;

class ManagerTests extends TestCase
{
    /**
     * @test
     */
    public function make()
    {
        $manager = $this->getManager($singleton = false);

        $this->assertEquals($manager->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver()->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver('foo')->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver('bar')->doAnything(), 'do_anything_from_bar_driver');
    }

    /**
     * @test
     */
    public function make_with_singleton_drivers()
    {
        $manager = $this->getManager($singleton = true);

        $manager->driver('foo')->doAnything();
        $manager->driver('foo')->doAnything();
        $manager->driver('foo')->doAnything();

        $this->assertEquals($manager->driver('foo')->doAnything(), 'do_anything_from_foo_driver');
    }

    /**
     * @test
     */
    public function make_with_unexpected_driver()
    {
        $manager = $this->getManager($singleton = false);

        $this->expectException(DriverResolutionException::class);

        $manager->driver('unexpected');
    }

    /**
     * @return Anonymous
     */
    protected function getManager(bool $needSingleton)
    {
        return new class($needSingleton) extends Manager {

            public function __construct(bool $needSingleton)
            {
                parent::__construct();

                $this->singleton = $needSingleton;
            }

            public function createFooDriver()
            {
                return new class {

                    public function doAnything()
                    {
                        return 'do_anything_from_foo_driver';
                    }
                };
            }

            public function createBarDriver()
            {
                return new class {

                    public function doAnything()
                    {
                        return 'do_anything_from_bar_driver';
                    }
                };
            }

            public function getDefaultDriver()
            {
                return 'foo';
            }
        };
    }
}
