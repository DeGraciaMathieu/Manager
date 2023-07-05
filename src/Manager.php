<?php

namespace DeGraciaMathieu\Manager;

use DeGraciaMathieu\Manager\Exceptions\DriverResolutionException;

abstract class Manager
{
    public function __construct(
        protected bool $singleton = false,
        protected Aggregator $aggregator = new Aggregator(),
    ) {}

    /**
     * Get the default driver name.
     */
    abstract public function getDefaultDriver(): string;

    /**
     * Dynamically call the default driver instance.
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->driver()->$method(... $parameters);
    }

    /**
     * Get a driver instance.
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    public function driver(string $name = null): mixed
    {
        $name = $name ?: $this->getDefaultDriver();

        $driver = $this->load($name);

        return $driver;
    }

    /**
     * Load a driver instance.
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    protected function load(string $name): mixed
    {
        if ($this->singleton) {
            return $this->loadWithSingleton($name);
        }

        return $this->makeDriverInstance($name);
    }

    /**
     * Load a singleton driver instance.
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException
     */
    protected function loadWithSingleton(string $name): mixed
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
     * Make a new driver instance.
     *
     * @throws \DeGraciaMathieu\Manager\Exceptions\DriverResolutionException
     */
    protected function makeDriverInstance(string $name): mixed
    {
        $method = 'create' . ucfirst(strtolower($name)) . 'Driver';

        if (! method_exists($this, $method)) {
            throw new DriverResolutionException('Driver [' . $name . '] not supported.');
        }

        return $this->$method();
    }
}
