<?php

namespace macchiato_academy\core\database;

use macchiato_academy\app\entity\IEntity;
use macchiato_academy\app\exceptions\NotFoundException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\core\App;
use PDOException;
use PDO;

class QueryBuilder
{
    private $connection;
    private $table;
    private $classEntity;

    public function __construct(string $table, string $classEntity)
    {
        $this->connection = App::getConnection();
        $this->table = $table;
        $this->classEntity = $classEntity;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->table";
        return $this->executeQuery($sql);
    }

    public function find(int $id): IEntity
    {
        $sql = "SELECT * FROM $this->table WHERE id=$id";
        $result = $this->executeQuery($sql);
        if (empty($result))
            throw new NotFoundException("Didn't found any element with id $id");
        return $result[0];
    }

    public function findBy(array $filters): array
    {
        $sql = "SELECT * FROM $this->table " . $this->getFilters($filters);

        return $this->executeQuery($sql, $filters);
    }

    public function getFilters(array $filters): string
    {
        if (empty($filters)) return '';
        $strFilters = [];
        foreach ($filters as $key => $value)
            $strFilters[] = $key . '=:' . $key;
        return ' WHERE ' . implode(' AND ', $strFilters);
    }

    public function findOneBy(array $filters): ?IEntity
    {
        $result = $this->findBy($filters);
        if (count($result) > 0)
            return $result[0];
        return null;
    }

    public function save(IEntity $entity): void
    {
        try {
            $parametrers = $entity->toArray();
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

    private function executeQuery(string $sql, array $parameters = []): array
    {
        $pdoStatement = $this->connection->prepare($sql);
        if ($pdoStatement->execute($parameters) === false)
            throw new QueryException("No se ha podido ejecutar la query solicitada.");
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $this->classEntity);
    }

    public function executeTransaction(callable $fnExecuteQuerys)
    {
        try {
            $this->connection->beginTransaction();
            $fnExecuteQuerys(); // LLamamos a todas las consultas SQL de la transacción
            $this->connection->commit();
        } catch (PDOException $pdoException) {
            $this->connection->rollBack(); // Se deshacen todos los cambios desde beginTransaction()
            throw new QueryException("No se ha podido realizar la operación.");
        }
    }

    public function getUpdates(array $parameters)
    {
        $updates = '';
        foreach ($parameters as $key => $value) {
            if ($key !== 'id')
                if ($updates !== '')
                    $updates .= ", ";
            $updates .= $key . '=:' . $key;
        }
        return $updates;
    }
    public function update(IEntity $entity): void
    {
        try {
            $parameters = $entity->toArray();
            $sql = sprintf(
                'UPDATE %s SET %s WHERE id=:id',
                $this->table,
                $this->getUpdates($parameters)
            );
            $statement = $this->connection->prepare($sql);
            $statement->execute($parameters);
        } catch (PDOException $pdoException) {
            throw new QueryException("No se ha podido actualizar el elemento con id " . $parameters['id']);
        }
    }
}