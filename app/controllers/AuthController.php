<?php
namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\UserRepository;


class AuthController {
    public function login() {
        // $errores = FlashMessage::get('login-error', []);
        // $email = FlashMessage::get('email');
        $title = "Login | Macchiato Academy";

        Response::renderView(
            'login',
            compact('title')
        );
    }

    public function checkLogin() {
        try {
            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Usuario no puede estar vacio');

           
            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Contraseña no puede estar vacia');

            $usuario = App::getRepository(UserRepository::class)->findOneBy([
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ]);

            if (!is_null($usuario)) {
                //Guardamos el usuario en la sesion y redireccionamos a la pagina principal
                $_SESSION['loggedUser'] = $usuario->getId();
                
                App::get('router')->redirect('');
            }

            throw new ValidationException('El usuario o la contraseña introducidos no existen');
        } catch (ValidationException $validationException) {
            
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