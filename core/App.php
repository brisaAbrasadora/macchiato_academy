<?php
namespace macchiato_academy\core;

use macchiato_academy\app\exceptions\AppException;

class App {
    private static $container = [];

    public static function bind(string $key, $value) {
        static::$container[$key] = $value;
    }

    public static function get($key) {
        if(!array_key_exists($key, static::$container))
            throw new AppException("No se ha encontrado la clave $key en el contenedor");
        return static::$container[$key];
    }
}