<?php
namespace macchiato_academy\app\repository;

use Exception;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\StudentJoinsCourse;
use macchiato_academy\app\entity\Student;
use PDO;

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

    public function sign(int $id_student, int $id_course) {
        try {
            $parametrers = [
                "id_student" => $id_student,
                "id_course" => $id_course
            ];
            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $this->table,
                implode(', ', array_keys($parametrers)),
                ':' . implode(', :', array_keys($parametrers))
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parametrers);
        } catch (PDOException $exception) {
            throw new QueryException("Only the students can enroll in a course");
        }
    }

    public function getStudents(int $id_course) {
        try {
            $parameters = [
                "id_course" => $id_course
            ];
            $sql =  "SELECT * FROM {$this->table} " .
                    $this->getFilters($parameters);
            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
            return $statement->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $exception) {
            throw new QueryException("Something went wrong at getStudents");
        }
    }

    public function countNumOfStudents(int $id) {
        $parameters = [
            "id_course" => $id
        ];
        $sql = sprintf(
            "SELECT COUNT(*) as NumOfStudents FROM %s %s",
            $this->table,
            $this->getFilters($parameters)
        );
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetch(PDO::FETCH_ASSOC)['NumOfStudents'];
    }

    public function isStudentEnrolled(array $whereClause): array {
        $sql =  "SELECT * FROM $this->table " .
                $this->getFilters($whereClause);

        $pdoStatement = $this->connection->prepare($sql);
        if ($pdoStatement->execute($whereClause) === false)
            throw new QueryException("No se ha podido ejecutar la query solicitada.");
        $result = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        if ($result === false)
            return [];
        return $result;
    }
}
