<?php

namespace DeGraciaMathieu\Manager;

use DeGraciaMathieu\Manager\Exceptions\DriverOverwrittenException;

final class Aggregator
{
    public function __construct(
        protected array $drivers = [],
    ) {}

    /**
     * Set driver instance
     */
    public function set(string $name, $driver): void
    {
        if ($this->has($name)) {
            throw new DriverOverwrittenException('Driver [' . $name . '] already registered.');
        }

        $this->drivers[$name] = $driver;
    }

    /**
     * Get driver instance
     */
    public function get(string $name): mixed
    {
        return $this->drivers[$name];
    }

    /**
     * Check if driver instance exists
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->drivers);
    }   

    /**
     * Get all drivers instance
     */
    public function all(): array
    {
        return $this->drivers;
    }           
}
