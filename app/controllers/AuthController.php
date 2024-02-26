<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\entity\User;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\core\Security;
use macchiato_academy\app\entity\Student;
use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\app\exceptions\LanguageException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\repository\LanguageRepository;
use Exception;
use macchiato_academy\app\utils\File;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\app\repository\ImageRepository;
use DateTime;

class AuthController
{
    public function login()
    {
        $errors = FlashMessage::get('login-error', []);
        $email = FlashMessage::get('email');
        $title = "Login | Macchiato Academy";

        Response::renderView(
            'login',
            compact('title', 'errors', 'email')
        );
    }

    public function checkLogin()
    {
        try {
            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');

            FlashMessage::set('email', $_POST['email']);

            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Password can\'t be empty');

            $usuario = App::getRepository(UserRepository::class)->findOneBy([
                'email' => $_POST['email'],
            ]);

            if (!is_null($usuario) && Security::checkPassword($_POST['password'], $usuario->getPassword())) {
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

    public function registerStudent()
    {
        $errors = FlashMessage::get('register-error', []);
        $email = FlashMessage::get('email');
        $username = FlashMessage::get('username');
        
        $langSelected = FlashMessage::get('languageSelected');
        $title = "Sign-up | Macchiato Academy";

        try {
            $languageRepository = App::getRepository(LanguageRepository::class);
            $languages = $languageRepository->findAll();
        } catch (QueryException $queryException) {
            FlashMessage::set('errores', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errores', [$appException->getMessage()]);
        } catch (LanguageException $categoriaException) {
            FlashMessage::set('errores', [$categoriaException->getMessage()]);
        } catch (Exception $exception) {
            FlashMessage::set('errores', [$exception->getMessage()]);
        }

        FlashMessage::unset('errors');
        FlashMessage::unset('mail');

        Response::renderView(
            'sign-up',
            compact('title', 'errors', 'email', 'langSelected', 'languages')
        );
    }

    public function checkRegisterStudent()
    {
        try {
            if (!isset($_POST['username']) || empty($_POST['username']))
                throw new ValidationException('Username can\'t be empty');
            FlashMessage::set('username', $_POST['username']);

            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');
            FlashMessage::set('email', $_POST['email']);


            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Password can\'t be empty');

            if (
                !isset($_POST['passwordConfirm'])
                || empty($_POST['passwordConfirm'])
                || $_POST['password'] !== $_POST['passwordConfirm']
            )
                throw new ValidationException('Both passwords must match');

            $student = new User();

            // if (!isset($_POST['profilePicture'])) {
            //     $typeFile = ['image/jpeg', 'image/gif', 'image/png'];
            //     $pfpFile = new File('profilePicture', $typeFile);
            //     $pfpFile->saveUploadFile(ProfilePicture::PROFILE_PICTURES_ROUTE);
            //     $image = new Image($pfpFile->getFileName());
            //     App::getRepository(ImageRepository::class)->save($image);
            //     $image = App::getRepository(ImageRepository::class)->findOneBy([
            //         "image_name" => $pfpFile->getFileName(),
            //     ]);
            //     $profilePicture = new ProfilePicture(
            //         $image->getId(), 
            //         $image->getImageName(), 
            //         $image->getId(),
            //         $user->getId());

            // } else {
            //     $student->setProfilePicture(1);
            // }

            $student->setProfilePicture(1);
            $password = Security::encrypt($_POST['password']);
            $student->setUsername($_POST['username']);
            $student->setEmail($_POST['email']);
            $student->setPassword($password);
            $student->setRole('ROLE_STUDENT');
            $dateOfBirth = !empty($_POST['dateOfBirth']) ? new DateTime($_POST['dateOfBirth']) : null;
            if (isset($dateOfBirth))    $student->setDateOfBirth($dateOfBirth->format('Y-m-d H:i:s'));
            $dateOfJoin = new DateTime();
            $student->setDateOfJoin($dateOfJoin->format('Y-m-d H:i:s'));
            $student->setFavoriteLanguage(!empty($_POST['favoriteLanguage']) ? $_POST['favoriteLanguage'] : null);
            App::getRepository(UserRepository::class)->save($student);
            FlashMessage::unset('username');
            FlashMessage::unset('email');

            $message = 'Student ' . $student->getUsername() . ' created';
            FlashMessage::set('message', $message);

            App::get('router')->redirect('login');
        } catch (ValidationException $validationException) {
            FlashMessage::set('registro-error', [$validationException->getMessage()]);
            App::get('router')->redirect('sign-up');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['loggedUser'])) {
            unset($_SESSION['loggedUser']);
        }

        App::get('router')->redirect('login');
    }
}
