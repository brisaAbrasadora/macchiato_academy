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

    public function __construct(string $profilePicture = '', string $email = '', string $username = '', string $password = '', string $role = '') {
        $this->id = null;
        $this->dateOfBirth = null;
        $this->profilePicture = $profilePicture;
        $this->email = $email;
        $this->username = $username;
        $this->dateOfJoin = new DateTime('now', new DateTimeZone("Europe/Madrid"));
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public function getemail(): string
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [];
    }


}