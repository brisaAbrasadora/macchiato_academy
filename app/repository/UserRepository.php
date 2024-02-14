<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\User;
use macchiato_academy\core\database\QueryBuilder;

class UserRepository extends QueryBuilder {
    public function __construct(string $table = 'users', string $classEntity = User::class) {
        parent::__construct($table, $classEntity);
    }
}