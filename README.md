<p align="center">
<a href="https://scrutinizer-ci.com/g/DeGraciaMathieu/Manager/"><img src="https://scrutinizer-ci.com/g/DeGraciaMathieu/Manager/badges/build.png?b=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/DeGraciaMathieu/manager/?branch=master"><img src="https://scrutinizer-ci.com/g/DeGraciaMathieu/manager/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/degraciamathieu/manager"><img src="https://img.shields.io/packagist/v/degraciamathieu/manager.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href='https://packagist.org/packages/degraciamathieu/manager'><img src='https://img.shields.io/packagist/dt/degraciamathieu/manager.svg?style=flat-square' /></a> 
</p>

# DeGraciaMathieu/Manager

Implementation of the Manager pattern existing in Laravel framework.

* [Installation](#installation)
* [Usage](#usage)
* [Work with singleton](#work-with-singleton)
* [Example with Laravel](#example-with-laravel)

## Installation
 
| Manager     | 3.*                | 2.*                | 1.*                |
|-------------|--------------------|--------------------|--------------------|
| php ^8.1    | :white_check_mark: | :x:                | :x:                |
| php 8.0.*   | :x:                | :white_check_mark: | :x:                |
| php 7.*     | :x:                | :x:                | :white_check_mark: |
 
```
composer require degraciamathieu/manager
```

## Usage

This package offers an abstract class `DeGraciaMathieu\Manager\Manager` which needs to be extended to implement the creation of various Driver classes :

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

class WeatherManager extends Manager {

    public function createOpenweathermapDriver() 
    {
        return new Openweathermap();
    }

    public function getDefaultDriver(): string
    {
        return 'openweathermap';
    }
}
```

A driver is a class integrating all the logic of an implementation, in our examples the interactions with the APIs of [Openweathermap](https://openweathermap.org/api) :

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

class Openweathermap {

    public function itsRainingNow(string $city): bool
    {   
        // call Openweathermap api to know if it is raining in this city

        return true;
    }
}
```
From now on, you can directly call the method of a driver directly from the manager :

```php
(new WeatherManager())->itsRainingNow('Paris'); // true
```

The manager will call the `itsRainingNow` method of the default driver configured by the `getDefaultDriver` method.

You can also call any driver from the manager's `driver` method :

```php
(new WeatherManager())->driver('openweathermap')->itsRainingNow('Paris');
```

Now if you want to create a new implementation, for example if you want to use [Aerisweather](https://www.aerisweather.com/develop/api/) APIs, you just have to create a new driver in your manager :

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

class WeatherManager extends Manager {

    public function createOpenweathermapDriver()
    {
        return new Openweathermap();
    }

    public function createAerisweatherDriver()
    {
        return new Aerisweather();
    }

    public function getDefaultDriver(): string
    {
        return 'openweathermap';
    }
}
```
## Add an interface to the drivers 

For more consistency it is advisable to implement an interface to the different drivers :

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

interface Driver {
    public function itsRainingNow(string $city): bool;
}
```

You obviously need to add this interface to your drivers.

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

class WeatherManager extends Manager {

    public function createOpenweathermapDriver(): Driver
    {
        return new Openweathermap();
    }

    public function createAerisweatherDriver(): Driver
    {
        return new Aerisweather();
    }

    public function getDefaultDriver(): string
    {
        return 'openweathermap';
    }
}
```

Now you will be assured that each driver instantiated by the manager will have the same interface.

## Repository class

To control side effects of drivers, it is advisable to create a class encapsulating the instance of a driver, this class is usually called `Repository` :

```php
namespace App\Managers;

use DeGraciaMathieu\Manager\Manager;

class WeatherManager extends Manager {

    public function createOpenweathermapDriver(): Repository
    {
        $driver = new Openweathermap();

        return new Repository($driver);
    }

    public function createAerisweatherDriver(): Repository
    {
        $driver = new Aerisweather();

        return new Repository($driver);
    }

    public function getDefaultDriver(): string
    {
        return 'openweathermap';
    }
}
```

The repository is a class providing a bridge between your application and the driver :

```php
namespace App\Managers;

class Repository {

    public function __construct(
        private Driver $driver,
    ){}

    public function itsRainingNow(string $city): bool
    {
        return $this->driver->itsRainingNow($city);
    }
}
```
Thus, your application will never be aware of which driver it is handling, because it will always be encapsulated in a class repository.

The repository is also a good place if you need to add specific logic for all drivers.

## Work with singleton

You can also cache the creation of Drivers with the `$singleton` property.

With the `singleton` property you will only create one instance of `Openweathermap` driver :

```php
<?php

$weatherManager = new WeatherManager(singleton: true);

$weatherManager->driver('openweathermap')->itsRainingNow('Paris');
$weatherManager->driver('openweathermap')->itsRainingNow('Paris');
$weatherManager->driver('openweathermap')->itsRainingNow('Paris');
```

> by default, singleton property value is False

## Example with Laravel

[Usage example](https://github.com/DeGraciaMathieu/manager-examples) of the pattern manager in a Laravel project.
