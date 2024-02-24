<?php
namespace macchiato_academy\app\entity;

class Image implements IEntity {
    const PROFILE_PICTURES_ROUTE = '/public/img/profilePictures/';

    protected ?int $id;
    protected string $image_name;

    public function __construct(?int $id = null, string $image_name = '') {
        $this->id = $id;
        $this->image_name = $image_name;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getImageName(): string {
        return $this->image_name;
    }

    public function getProfilePicturesPath(): string {
        return self::PROFILE_PICTURES_ROUTE . $this->getImageName();
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'image_name' => $this->getImageName(),
        ];
    }
}