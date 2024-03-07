<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Student;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\entity\Course;

class StudentRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'student', string $classEntity = Student::class)
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

    public function unsign(Course $course) {
        try {
            $parameters = ["id_course" => $course->getId()];
            $sql = sprintf(
                "DELETE FROM %s WHERE %s",
                "student_joins_course",
                implode(", ", array_keys($parameters)),
                ":" . implode(", :", array_keys($parameters))
            );

            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (PDOException $exception) {
            throw new QueryException("Error al insertar en la base de datos.");
        }
    }

    public function findInCourse(array $whereClause)
    {
        $userKeys = array_map(fn ($key): string => "user.$key", array_keys((new Student())->toArray()));
        $sql =  "SELECT " . implode(", ", $userKeys) . " FROM user " .
                "INNER JOIN student_joins_course " .
                "ON id_student = user.id " . 
                "INNER JOIN course " . 
                "ON id_course = course.id " . 
                $this->getFilters($whereClause);
        return $this->executeQuery($sql, $whereClause);
    }
}
