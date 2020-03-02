# DeGraciaMathieu/Manager

## Installation
 
Run in console below command to download package to your project:

```
composer require degraciamathieu/manager
```

## Usage

This package offers an abstract class `Manager` which needs to be extended to implement the creation of various Driver classes

```php

use DeGraciaMathieu/Manager/Manager;

class LoggerManager extends Manager {

    public function createMonologDriver(): LoggerDriver
    {
        return new MonologDriver();
    }

    public function createMockDriver(): LoggerDriver
    {
        return new MockDriver();
    }

    public function getDefaultDriver()
    {
        return 'monolog';
    }
}
```

In a matter of consistency, all Driver creations (`createClientDriver`, `createMockDriver`...) should return a class which itself implements the same interface, the `LoggerDriver` contract in this here case.

```php

interface LoggerDriver {
    public function doAnything();
}

class MonologDriver implements LoggerDriver {

    public function doAnything()
    {
        echo 'i do anything from the monolog driver';
    }
}

class MockDriver implements LoggerDriver {

    public function doAnything()
    {
        echo 'i do anything from the mock driver';
    }
}
```

The `getDefaultDriver` method should also be implemented in your class `Manager`, in order to determine which driver has to be created by default. It's also the right spot to determine the default driver from an environment variable, or a configuration.

```php
public function getDefaultDriver()
{
    return env('MANAGER_LOGGER_DEFAULT_DRIVER');
}
```

From now on, it's possible to use your `Manager`, either by using the default driver:

```php
(new LoggerManager())->doAnything(); // i do anything from the monolog driver
```

Or by simply specify the driver which needs to be instantiated.

```php
(new LoggerManager())->driver('monolog')->doAnything(); // i do anything from the monolog driver
(new LoggerManager())->driver('mock')->doAnything(); // i do anything from the mock driver
```

## Example:

Usage example of the pattern manager in a Laravel project
