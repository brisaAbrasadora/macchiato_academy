<?php

return [
    'database' => [
        'name' => 'academia',
        'username' => 'userCurso',
        'password' => 'php',
        'connection' => 'mysql:host=localhost',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ]
    ],
    'project' => [
        'namespace' => 'macchiato_academy'
    ],
    'routes' => [
        'filename' => 'routes.php'
    ],
    'security' => [
        'roles' => [
            'ROLE_ADMIN' => 4,
            'ROLE_TEACHER' => 3,
            'ROLE_STUDENT' => 2,
            'ROLE_ANONYMOUS' => 1
        ]
    ]
];
