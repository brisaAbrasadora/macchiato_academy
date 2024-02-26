<?php
namespace macchiato_academy\app\entity;

class ProfilePicture extends Image implements IEntity {
    const PROFILE_PICTURES_ROUTE = '/public/img/profilePictures/';

    private ?int $id_image;
    private ?int $id_user;

    public function __construct(int $id = 0, string $image_name = '', 
    ?int $id_image = null, ?int $id_user = null) {
        parent::__construct($id, $image_name);
        $this->id_image = $id_image;
        $this->id_user = $id_user;
    }

    public function getIdImage(): ?int {
        return $this->id_image;
    }

    public function getIdUser(): ?int {
        return $this->id_user;
    }

    public function setIdImage(?int $id): ProfilePicture {
        $this->id_image = $id;
        return $this;
    }

    public function setIdUser(?int $id_user): ProfilePicture {
        $this->id_user = $id_user;
        return $this;
    }

    public function getProfilePicturesPath(): string {
        return self::PROFILE_PICTURES_ROUTE . $this->getImageName();
    }

    public function toArray(): array {
        return [
            'id_image' => $this->getIdImage(),
            'image_name' => $this->getImageName(),
            'id_user' => $this->getIdUser()
        ];
    }

    public function __toString(): string {
        return implode(", ", $this->toArray());
    }
}