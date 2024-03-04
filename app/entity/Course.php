<?php

namespace macchiato_academy\app\entity;

class Course implements IEntity
{
    protected ?int $id;
    protected string $title;
    protected string $description;
    protected int $language;
    protected int $teacher;
    protected int $picture;

    public function __construct(string $title = '', string $description = '', int $language = 0, int $teacher = 0, int $picture = 0)
    {
        $this->id = null;
        $this->title = $title;
        $this->description = $description;
        $this->language = $language;
        $this->teacher = $teacher;
        $this->picture = $picture;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLanguage(): int
    {
        return $this->language;
    }

    public function getTeacher(): string
    {
        return $this->teacher;
    }

    public function getPicture(): int
    {
        return $this->picture;
    }

    public function setTitle(string $title): Course
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): Course
    {
        $this->description = $description;
        return $this;
    }

    public function setTeacher(int $teacher): Course
    {
        $this->teacher = $teacher;
        return $this;
    }

    public function setLanguage(int $language): Course
    {
        $this->language = $language;
        return $this;
    }

    public function setPicture(int $picture): Course
    {
        $this->picture = $picture;
        return $this;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'teacher' => $this->getTeacher(),
            'language' => $this->getLanguage(),
            'picture' => $this->getPicture()
        ];
    }
}
