<?php
namespace macchiato_academy\app\entity;

class Image implements IEntity {
    const PROFILE_PICTURES_ROUTE = '/public/img/profilePictures/';

    private ?int $id;
    private string $name;

    public function __construct(string $name = '') {
        $this->id = null;
        $this->name = $name;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getProfilePicturesPath(): string {
        return self::PROFILE_PICTURES_ROUTE . $this->getName();
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}