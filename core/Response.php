<?php
namespace macchiato_academy\core;

class Response {
    public static function renderView(string $name, array $data = [], string $layout = 'layout') {
        extract($data);
        $app['user'] = App::get('appUser');
        ob_start();
        require __DIR__ . "/../app/views/$name.view.php";
        $mainContent = ob_get_clean();
        require __DIR__ . "/../app/views/$layout.view.php";
    }
}