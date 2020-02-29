<?php

namespace DeGraciaMathieu\Manager\Tests\Templates;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use DeGraciaMathieu\Manager\Manager;

class ManagerTests extends TestCase
{
    /** 
     * @test
     */
    public function make()
    {
        $manager = $this->getManager();

        $this->assertEquals($manager->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver()->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver('foo')->doAnything(), 'do_anything_from_foo_driver');
        $this->assertEquals($manager->driver('bar')->doAnything(), 'do_anything_from_bar_driver');
    }

    /** 
     * @test
     */
    public function makeWithUnexpectedDriver()
    {
        $manager = $this->getManager();

        $this->expectException(InvalidArgumentException::class);

        $manager->driver('unexpected');
    }

    /**
     * @return Anonymous 
     */
    protected function getManager()
    {
        return new class extends Manager {

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
