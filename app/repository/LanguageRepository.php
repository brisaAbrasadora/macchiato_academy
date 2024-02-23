<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Language;
use macchiato_academy\core\database\QueryBuilder;

class LanguageRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'language', string $classEntity = Language::class)
    {
        parent::__construct($table, $classEntity);
    }
}
