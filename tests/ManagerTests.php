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

        $this->assertEquals($manager->driver(), 'foo_driver');
        $this->assertEquals($manager->driver('foo'), 'foo_driver');
        $this->assertEquals($manager->driver('bar'), 'bar_driver');
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
                return 'foo_driver';
            }

            public function createBarDriver()
            {
                return 'bar_driver';
            }            

            public function getDefaultDriver()
            {
                return 'foo';
            }
        };
    }
}
