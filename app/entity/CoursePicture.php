<?php
namespace macchiato_academy\app\entity;

class CoursePicture extends Image implements IEntity {
    const COURSE_PICTURES_ROUTE = '/public/img/coursePictures/';

    protected ?int $id;
    private ?int $id_course;

    public function __construct(int $id = 0, string $name = '', ?int $id_course = null) {
        parent::__construct($name, $id);
        $this->id = $id;
        $this->id_course = $id_course;
    }

    public function getIdUser(): ?int {
        return $this->id_course;
    }

    public function setIdUser(?int $id_course): CoursePicture {
        $this->id_course = $id_course;
        return $this;
    }

    public function getCoursePicturesPath(): string {
        return self::COURSE_PICTURES_ROUTE . $this->getName();
    }

    public function deleteFile(): bool {
        return unlink($_SERVER['DOCUMENT_ROOT'] . $this->getCoursePicturesPath());
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            // 'name' => $this->getName(),
            'id_course' => $this->getIdUser()
        ];
    }

    public function __toString(): string {
        return implode(", ", $this->toArray());
    }
}