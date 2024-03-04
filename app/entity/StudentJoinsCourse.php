<?php
namespace macchiato_academy\app\entity;

class StudentJoinsCourse implements IEntity {

    public function __construct() {
    }

    public function toArray(): array {
        return [
        ];
    }

    public function __toString(): string {
        return implode(", ", $this->toArray());
    }
}