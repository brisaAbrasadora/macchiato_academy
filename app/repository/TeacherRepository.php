<?php
namespace macchiato_academy\app\repository;

use macchiato_academy\app\entity\Teacher;
use macchiato_academy\core\database\QueryBuilder;
use macchiato_academy\app\entity\IEntity;
use PDOException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\core\App;

class TeacherRepository extends QueryBuilder
{
    /**
     * @param string $table
     * @param string $classEntity
     */
    public function __construct(string $table = 'teacher', string $classEntity = Teacher::class)
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

    public function findAll(): array {
        $teacherIds = array_map(
            function ($teacher) {
                return $teacher->getId();
            },
            (new QueryBuilder($this->table, $this->classEntity))->findAll()
        );
        $teacher = new Teacher();
        $userKeys = array_map(fn ($key): string => "user.$key", array_keys($teacher->toArray()));
        array_push($userKeys, "teacher.id");

        $teachers = [];
        foreach ($teacherIds as $teacherId) {
            $teacher = $this->findInnerJoin(
                $userKeys,
                "user",
                [
                    "user.id",
                    "teacher.id"
                ],
                [
                    "teacher__id" => $teacherId
                ]
            );
            array_push($teachers, $teacher);
        }

        return $teachers;
    }
}
