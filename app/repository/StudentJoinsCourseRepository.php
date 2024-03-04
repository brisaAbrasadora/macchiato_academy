<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\StudentJoinsCourse;
use macchiato_academy\app\entity\Student;

class StudentJoinsCourseRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'student_joins_course', string $classEntity = StudentJoinsCourse::class)
    {
        parent::__construct($table, $classEntity);
    }

    public function save(IEntity $entity): void {
        try {
            $parametrers = ["id" => $entity->getId()];

            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $this->table,
                implode(', ', array_keys($parametrers)),
                ':' . implode(', :', array_keys($parametrers))
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parametrers);
        } catch (PDOException $exception) {
            throw new QueryException("Error al insertar en la base de datos.");
        }
    }

    public function unsign(Student $student, Course $course) {
        try {
            $parameters = ["id_course" => $course->getId(), "id_student" => $student->getId()];
            $sql = sprintf(
                "DELETE FROM %s %s",
                "student_joins_course",
               $this->getFilters($parameters)
            );

            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (PDOException $exception) {
            throw new QueryException("Error al insertar en la base de datos.");
        }
    }
}
