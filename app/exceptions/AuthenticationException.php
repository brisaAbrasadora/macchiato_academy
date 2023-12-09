<?php
namespace macchiato_academy\app\exceptions;

use macchiato_academy\exceptions\AppException;

class AuthenticationException extends AppException {
    public function __construct(string $message = '', int $code = 403) {
        parent::__construct($message, $code);
    }
}