<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\User;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;

class UserRepository extends QueryBuilder {
    public function __construct(string $table = 'user', string $classEntity = User::class) {
        parent::__construct($table, $classEntity);
    }

    public function emailExists(string $email): bool {
        $user = $this->findOneBy([
            "email" => $email
        ]);

        return isset($user);
    }
}