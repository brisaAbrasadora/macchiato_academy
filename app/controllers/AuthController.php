<?php
namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\helpers\FlashMessage;


class AuthController {
    public function login() {
        $errors = FlashMessage::get('login-error', []);
        $email = FlashMessage::get('email');
        $title = "Login | Macchiato Academy";

        Response::renderView(
            'login',
            compact('title', 'errors', 'email')
        );
    }

    public function checkLogin() {
        try {
            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');

            FlashMessage::set('email', $_POST['email']);
           
            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Password can\'t be empty');

            $usuario = App::getRepository(UserRepository::class)->findOneBy([
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);

            if (!is_null($usuario)) {
                //Guardamos el usuario en la sesion y redireccionamos a la pagina principal
                $_SESSION['loggedUser'] = $usuario->getId();
                
            FlashMessage::unset('email');
            App::get('router')->redirect('');
            }

            throw new ValidationException('El usuario o la contraseña introducidos no existen');
        } catch (ValidationException $validationException) {
            FlashMessage::set('login-error', [$validationException->getMessage()]);
            App::get('router')->redirect('login');
        }
    }

    public function logout() {
        if (isset($_SESSION['loggedUser'])) {
            unset($_SESSION['loggedUser']);
        }

        App::get('router')->redirect('login');
    }
}