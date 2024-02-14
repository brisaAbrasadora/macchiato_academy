<?php

namespace macchiato_academy\app\entity;

class Teacher extends User implements IEntity
{
    protected $coursesMade;

    public function __construct(
        string $profilePicture = '',
        string $email = '',
        string $username = '',
        string $password = '',
        string $role = 'TEACHER_ROLE',
        array $coursesMade = []
    ) {
        parent::__construct($profilePicture, $email, $username, $password, $role);
        $this->coursesMade = $coursesMade;
    }

    public function getCoursesMade(): array {
        return $this->coursesMade;
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
            'coursesMade' => $this->getCoursesMade(),
        ];
    }
}
