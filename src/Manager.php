<?php

namespace DeGraciaMathieu\Manager;

use DeGraciaMathieu\Manager\Exceptions\DriverResolutionException;

abstract class Manager
{
    /**
     * @var boolean
     */
    protected $singleton = false;

    /**
     * @var \DeGraciaMathieu\Manager\Aggregator
     */
    protected $aggregator;

    /**
     * Welcome
     */
    public function __construct()
    {
        $this->aggregator = new Aggregator();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    abstract public function getDefaultDriver();

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }

    /**
     * Get a driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        $driver = $this->load($name);

        return $driver;
    }

    /**
     * Load a driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    protected function load(string $name)
    {
        if ($this->singleton) {
            return $this->loadWithSingleton($name);
        }

        return $this->loadWithoutSingleton($name);
    }

    /**
     * Load a singleton driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    protected function loadWithSingleton(string $name)
    {
        $alreadyLoad = $this->aggregator->has($name);

        if ($alreadyLoad) {
            return $this->aggregator->get($name);
        }

        $driver = $this->makeDriverInstance($name);

        $this->aggregator->set($name, $driver);

        return $driver;
    }

    /**
     * Load a driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    protected function loadWithoutSingleton(string $name)
    {
        return $this->makeDriverInstance($name);
    }    

    /**
     * Make a new driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    protected function makeDriverInstance(string $name)
    {
        $method = 'create' . ucfirst(strtolower($name)) . 'Driver';

        if (! method_exists($this, $method)) {
            throw new DriverResolutionException('Driver [' . $name . '] not supported.');
        }

        return $this->$method();
    }
}
