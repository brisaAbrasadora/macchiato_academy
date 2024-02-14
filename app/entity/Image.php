<?php
namespace macchiato_academy\app\entity;

class Image implements IEntity {
    private $id;
    private $username;
    private $description;

    public function __construct(string $username = '', string $description = '') {
        $this->id = null;
        $this->name = $username;
        $this->description = $description;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'descriptioin' => $this->getDescription()
        ];
    }
}