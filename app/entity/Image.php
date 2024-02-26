<?php
namespace macchiato_academy\app\entity;

class Image implements IEntity {
    protected ?int $id;
    protected string $name;

    public function __construct(?int $id = null, string $name = '') {
        $this->id = $id;
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