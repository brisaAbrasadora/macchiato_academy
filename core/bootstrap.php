<?php

use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\App;
use macchiato_academy\core\Router;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$config = require_once __DIR__ . '/../app/config.php';
App::bind('config', $config);

$router = Router::load(__DIR__ . '/../app/' . $config['routes']['filename']);
App::bind('router', $router);

if (isset($_SESSION['loggedUser'])) {
    $appUser = App::getRepository(UserRepository::class)->findOneBy([
        "id" => $_SESSION['loggedUser']
    ]);

    if (empty($appUser)) {
        $appUser = null;
    }
} else {
    $appUser = null;
}

App::bind('appUser', $appUser);
