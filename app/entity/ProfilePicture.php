<?php
namespace macchiato_academy\app\entity;

class ProfilePicture extends Image implements IEntity {
    const PROFILE_PICTURES_ROUTE = '/public/img/profilePictures/';

    private ?int $id;
    private ?int $id_user;

    public function __construct(string $name = '') {
        parent::__construct($name);
        $this->id = null;
        $this->id_user = null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getIdUser(): ?int {
        return $this->id_user;
    }

    public function setId(?int $id): ProfilePicture {
        $this->id = $id;
        return $this;
    }

    public function setIdUser(?int $id_user): ProfilePicture {
        $this->id_user = $id_user;
        return $this;
    }

    public function getProfilePicturesPath(): string {
        return self::PROFILE_PICTURES_ROUTE . $this->getName();
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'id_user' => $this->getIdUser()
        ];
    }

    public function __toString(): string {
        return implode(", ", $this->toArray());
    }
}