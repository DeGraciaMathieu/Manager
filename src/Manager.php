<?php

namespace DeGraciaMathieu\Manager;

use InvalidArgumentException;

abstract class Manager
{
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
     * @throws \InvalidArgumentException
     */
    public function driver($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        $driver = $this->load($name);

        return $driver;
    }

    /**
     * Load a new driver instance.
     *
     * @param  string  $name
     * @throws \InvalidArgumentException
     * 
     * @return mixed
     */
    protected function load(string $name)
    {
        $method = 'create' . ucfirst(strtolower($name)) . 'Driver';

        if (! method_exists($this, $method)) {
            throw new InvalidArgumentException('Driver [' . $name . '] not supported.');
        }

        return $this->$method();
    }
}
