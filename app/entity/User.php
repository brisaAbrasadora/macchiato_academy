<?php
namespace macchiato_academy\app\entity;

class User implements IEntity {
    protected ?int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;
    protected int $profilePicture;
    protected ?string $dateOfBirth;
    protected string $dateOfJoin;
    protected ?string $favoriteLanguage;

   public function __construct(string $username = '', string $email = '', string $password = '',
   string $role = '', int $profilePicture = 1, string $dateOfJoin = '', ?string $favoriteLanguage = '')
   {
        $this->id = null;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->profilePicture = $profilePicture;
        $this->dateOfBirth = null;
        $this->dateOfJoin = $dateOfJoin;
        $this->favoriteLanguage = $favoriteLanguage;
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfilePicture(): ?int
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

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function getDateOfJoin(): string
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

    public function getFavoriteLanguage(): ?string
    {
        return $this->favoriteLanguage;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): User {
        $this->password = $password;
        return $this;
    }

    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    public function setRole(string $role): User {
        $this->role = $role;
        return $this;
    }

    public function setDateOfBirth(?string $dateOfBirth): User {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function setFavoriteLanguage(?string $favoriteLanguage): User {
        $this->favoriteLanguage = $favoriteLanguage;
        return $this;
    }

    public function setProfilePicture(int $profilePicture): User {
        $this->profilePicture = $profilePicture;
        return $this;
    }

    public function setDateOfJoin(string $dateOfJoin): User {
        $this->dateOfJoin = $dateOfJoin;
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