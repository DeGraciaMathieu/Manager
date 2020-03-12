<?php

namespace DeGraciaMathieu\Manager;

use DeGraciaMathieu\Manager\Exceptions\DriverResolutionException;

abstract class Manager
{
    /**
     * Driver aggregator instance.
     *
     * @var \DeGraciaMathieu\Manager\Aggregator
     */
    protected $aggregator;

    /**
     * Use cached driver cache.
     *
     * @var boolean
     */
    protected $cached = false;

    /**
     * Create a new Manager instance.
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
     * Get a driver instance.
     *
     * @param  string  $name
     * @return mixed
     */
    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->resolve($name);
    }

    /**
     * Resolve the given driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    public function resolve(string $name)
    {
        return $this->cached
            ? $this->makeCached($name)
            : $this->make($name);
    }

    /**
     * Make a driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    public function make(string $name)
    {
        return $this->makeDriverInstance($name);
    }

    /**
     * Make a cached driver instance.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    public function makeCached(string $name)
    {
        if ($this->aggregator->has($name)) {
            return $this->aggregator->get($name);
        }

        $driver = $this->make($name);
        $this->aggregator->set($name, $driver);

        return $driver;
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
        $method = "create".ucfirst(strtolower($name))."Driver";

        if ( ! method_exists($this, $method)) {
            throw new DriverResolutionException("Driver [$name] not supported.");
        }

        return $this->{$method}();
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->{$method}(...$parameters);
    }

    /**
     * Dynamically access driver.
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->driver($name);
    }
}
