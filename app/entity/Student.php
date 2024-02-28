<?php

namespace macchiato_academy\app\entity;

class Student extends User implements IEntity
{
    public function __construct(
        string $username = '',
        string $email = '',
        string $password = '',
        string $role = 'ROLE_STUDENT',
        int $profilePicture = 1,
        string $dateOfJoin = '',
    ) {
        parent::__construct($username, $email, $password, $role, $profilePicture, $dateOfJoin);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'email' => $this->getEmail(),
            'profilePicture' => $this->getProfilePicture(),
            'dateOfBirth' => $this->getDateOfBirth(),
            'dateOfJoin' => $this->getDateOfJoin(),
            'favoriteLanguage' => $this->getFavoriteLanguage(),
            'role' => $this->getRole(),
            'biography' => $this->getBiography()
        ];
    }
}
