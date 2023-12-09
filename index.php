<?php
use macchiato_academy\core\App;
use macchiato_academy\core\Request;
use macchiato_academy\app\exceptions\AppException;

try {
    require_once 'core/bootstrap.php';
    App::get('router')->direct(Request::uri(), Request::method());
} catch (AppException $appException) {
    $appException->handleError();
}

exit();
