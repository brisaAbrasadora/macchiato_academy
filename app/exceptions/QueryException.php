<?php
namespace macchiato_academy\app\exceptions;

use macchiato_academy\exceptions\AppException;

class QueryException extends AppException {
    public function __construct(string $message = '', int $code = 500) {
        parent::__construct($message, $code);
    }
}