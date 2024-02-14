<?php

namespace macchiato_academy\core;

use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\core\database\Connection;
use macchiato_academy\core\database\QueryBuilder;

class App
{
    private static $container = [];

    public static function bind(string $key, $value)
    {
        static::$container[$key] = $value;
    }

    public static function get($key)
    {
        if (!array_key_exists($key, static::$container))
            throw new AppException("No se ha encontrado la clave $key en el contenedor");
        return static::$container[$key];
    }

    public static function getConnection()
    {
        if (!array_key_exists('connection', static::$container))
            static::$container['connection'] = Connection::make();
        return static::$container['connection'];
    }

    public static function getRepository(string $className): QueryBuilder
    {
        if (!array_key_exists($className, static::$container))
            static::$container[$className] = new $className();

        return static::$container[$className];
    }
}
