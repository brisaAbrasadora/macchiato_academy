<?php
namespace macchiato_academy\app\exceptions;

use macchiato_academy\core\Response;
use Exception;

class AppException extends Exception {
    public function __construct(string $message = '', int $code = 500) {
        parent::__construct($message, $code);
    }

    private function getHttpHeaderMessage() {
        switch ($this->getCode()) {
            case 404:
                return '404 Page not found';
            case 403:
                return '403 Access denied';
            case 500:
                return '500 Server internal error';
        }
    }

    public function handleError() {
        try {
            $httpHeaderMessage = $this->getHttpHeaderMessage();

            $errorMessage = $this->getMessage();

            Response::renderView(
                'contact',
                compact ('httpHeaderMessage', 'errorMessage'),
            );
        } catch (Exception $exception) {
            die('An error occurred at out exceptions mannager');
        }
    }
}
