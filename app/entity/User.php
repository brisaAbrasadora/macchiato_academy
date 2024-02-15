<?php
namespace macchiato_academy\app\entity;

use DateTime;
use DateTimeZone;

class User implements IEntity {
    /**@var int */
    protected $id;
    /**@var string */
    protected $profilePicture;
    /**@var string */
    protected $email;
    /**@var string */
    protected $username;
    /**@var DateTime */
    protected $dateOfBirth;
    /**@var DateTime */
    protected $dateOfJoin;
    /**@var string */
    protected $password;
    /**@var string */
    protected $role;
    /**@var string */
    protected $favoriteLanguage;

    public function __construct(string $profilePicture = '', string $email = '', string $username = '', string $password = '', string $role = '', string $favoriteLanguage = '') {
        $this->id = null;
        $this->dateOfBirth = null;
        $this->profilePicture = $profilePicture;
        $this->email = $email;
        $this->username = $username;
        $this->dateOfJoin = null;
        $this->password = $password;
        $this->role = $role;
        $this->favoriteLanguage = $favoriteLanguage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getDateOfBirth(): DateTime
    {
        return $this->dateOfBirth;
    }

    public function getDateOfJoin(): DateTime
    {
        return $this->dateOfJoin;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getFavoriteLanguage(): string
    {
        return $this->role;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
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
        ];
    }


}