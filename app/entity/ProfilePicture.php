<?php
namespace macchiato_academy\app\entity;

class ProfilePicture extends Image implements IEntity {
    const PROFILE_PICTURES_ROUTE = '/public/img/profilePictures/';

    protected ?int $id;
    private ?int $id_user;

    public function __construct(int $id = 0, string $name = '', ?int $id_user = null) {
        parent::__construct($id, $name);
        $this->id = $id;
        $this->id_user = $id_user;
    }

    public function getIdUser(): ?int {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): ProfilePicture {
        $this->id_user = $id_user;
        return $this;
    }

    public function getProfilePicturesPath(): string {
        return self::PROFILE_PICTURES_ROUTE . $this->getName();
    }

    public function deleteFile(): bool {
        return unlink($this->getProfilePicturesPath());
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