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

    public function listCourses(array $whereClause): array {
        $courseKeys = array_map(fn ($key): string => "course.$key", array_keys((new Course())->toArray()));
        $sql =  "SELECT " . implode(", ", $courseKeys) . " FROM course " .
                "INNER JOIN student_joins_course " .
                "ON id_course = course.id " .
                $this->getFilters($whereClause);
        return $this->executeQuery($sql, $whereClause);
    }
}
