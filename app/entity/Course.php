<?php
namespace macchiato_academy\app\entity;

class Course implements IEntity {
    protected $id;
    protected $username;
    protected $description;
    protected $duration;
    protected $teacher;
    protected $students;

    public function __construct(string $username = '', string $description = '', string $duration = '', string $teacher = '', array $students) {
        $this->id = null;
        $this->name = $username;
        $this->description = $description;
        $this->duration = $duration;
        $this->teacher = $teacher;
        $this->students = $students;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function getTeacher(): string
    {
        return $this->teacher;
    }

    public function getStudents(): array
    {
        return $this->students;
    }

    public function toArray(): array
    {
         return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'duration' => $this->getduration(),
            'teacher' => $this->getTeacher(),
            'students' => $this->getStudents(),
        ];
    }
}