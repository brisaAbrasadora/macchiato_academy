<?php

namespace macchiato_academy\core\database;

use macchiato_academy\app\exceptions\AppException;
use PDO;
use PDOException;
use macchiato_academy\core\App;

class Connection
{
    public static function make()
    {
        try {
            $config = App::get('config')['database'];
            $connection = new PDO(
                $config['connection'] . ';dbname=' . $config['name'],
                $config['username'],
                $config['password'],
                $config['options'],
            );

            return $connection;
        } catch (PDOException $PDOException) {
            throw new AppException(("Couldn't connect to the DB"));
            return $connection;
        }
    }
}
