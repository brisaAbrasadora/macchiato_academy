<?php
namespace macchiato_academy\app\entity;

class Language implements IEntity {
    private $id;
    private $name;

    public function __construct(string $name = '') {
        $this->id = null;
        $this->name = $name;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function toArray(): array {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}