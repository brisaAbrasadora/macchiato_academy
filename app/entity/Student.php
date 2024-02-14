<?php

namespace macchiato_academy\app\entity;

class Student extends User implements IEntity
{
    protected $favoriteLanguage;
    protected $coursesSubscribed;

    public function __contruct(
        string $profilePicture = '',
        string $email = '',
        string $username = '',
        string $password = '',
        string $role = 'STUDENT_ROLE',
        string $favoriteLanguage = '',
        array $coursesSubscribed = []
    ) {
        parent::__construct($profilePicture, $email, $username, $password, $role);
        $this->favoriteLanguage = $favoriteLanguage;
        $this->coursesSubscribed = $coursesSubscribed;
    }

    public function getFavoriteLanguage(): string
    {
        return $this->favoriteLanguage;
    }

    public function getCoursesSubscribed(): array
    {
        return $this->coursesSubscribed;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'profilePicture' => $this->getProfilePicture(),
            'email' => $this->getemail(),
            'username' => $this->getUsername(),
            'dateOfBirth' => $this->getDateOfBirth(),
            'password' => $this->getPassword(),
            'role' => $this->getRole(),
            'favoriteLanguage' => $this->getFavoriteLanguage(),
            'coursesSubscribed' => $this->getCoursesSubscribed(),
        ];
    }
}
