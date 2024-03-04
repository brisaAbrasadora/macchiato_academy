<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Course;
use macchiato_academy\core\database\QueryBuilder;

class CourseRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'course', string $classEntity = Course::class)
    {
        parent::__construct($table, $classEntity);
    }
}
