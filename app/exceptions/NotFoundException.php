<?php
namespace macchiato_academy\app\exceptions;

use macchiato_academy\exceptions\AppException;

class NotFoundException extends AppException {
    public function __construct(string $message = '', int $code = 404) {
        parent::__construct($message, $code);
    }
}