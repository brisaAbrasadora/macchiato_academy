<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\core\Security;
use macchiato_academy\app\entity\Student;
use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\app\exceptions\LanguageException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\repository\LanguageRepository;
use Exception;
use DateTime;
use macchiato_academy\app\repository\StudentRepository;
use macchiato_academy\app\utils\Utils;

class AuthController
{
    public function login()
    {
        $errors = FlashMessage::get('login-error', []);
        $message = FlashMessage::get('message');
        $email = FlashMessage::get('email');
        $title = "Login | Macchiato Academy";

        FlashMessage::unset('login-error');
        FlashMessage::unset('message');

        Response::renderView(
            'login',
            compact('title', 'errors', 'email', 'message')
        );
    }

    public function checkLogin()
    {
        try {
            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');
            $email = htmlspecialchars(trim($_POST['email']));

            FlashMessage::set('email', $email);

            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Password can\'t be empty');

            $usuario = App::getRepository(UserRepository::class)->findOneBy([
                'email' => $email,
            ]);

            if (!is_null($usuario) && Security::checkPassword($_POST['password'], $usuario->getPassword())) {
                //Guardamos el usuario en la sesion y redireccionamos a la pagina principal
                $_SESSION['loggedUser'] = $usuario->getId();

                FlashMessage::unset('email');
                App::get('router')->redirect('');
            }

            throw new ValidationException('User or password doesn\'t exist');
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
            FlashMessage::set('errors', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errors', [$appException->getMessage()]);
        } catch (LanguageException $categoriaException) {
            FlashMessage::set('errors', [$categoriaException->getMessage()]);
        } catch (Exception $exception) {
            FlashMessage::set('errors', [$exception->getMessage()]);
        }

        FlashMessage::unset('errors');
        FlashMessage::unset('email');
        FlashMessage::unset('username');

        Response::renderView(
            'sign-up',
            compact('title', 'errors', 'username', 'email', 'langSelected', 'languages')
        );
    }

    public function checkRegisterStudent()
    {
        try {
            if (!isset($_POST['username']) || empty($_POST['username']))
                throw new ValidationException('Username can\'t be empty');
            $username = htmlspecialchars(trim($_POST['username']));
            FlashMessage::set('username', $username);

            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');
            $email = htmlspecialchars(trim($_POST['email']));
            if (!Utils::validateEmail($email))
                throw new ValidationException('Email format isn\'t correct');
            $emailExists = App::getRepository(UserRepository::class)
                ->emailExists($email);
            if ($emailExists)
                throw new ValidationException('Email alrady exists');
            FlashMessage::set('email', $email);

            if (!isset($_POST['password']) || empty($_POST['password']))
                throw new ValidationException('Password can\'t be empty');
            // THIS IS NOT THE BEST WAY TO DO THIS
            $password = htmlspecialchars(trim($_POST['password']));
            if (count(str_split($password)) < 5) 
                throw new ValidationException('Password must be at least 5 characters long');

            $passwordConfirm = htmlspecialchars(trim($_POST['passwordConfirm']));
            if (
                !isset($passwordConfirm)
                || empty($passwordConfirm)
                || $password !== $passwordConfirm
            )
                throw new ValidationException('Both passwords must match');

            $student = new Student();

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

            $password = Security::encrypt($password);
            $dateOfJoin = new DateTime();
            $favoriteLanguage = empty($_POST['favoriteLanguage']) ? null : $_POST['favoriteLanguage'];
            $student->setUsername($username)
                ->setEmail($email)
                ->setPassword($password)
                ->setDateOfJoin($dateOfJoin->format('Y-m-d H:i:s'))
                ->setFavoriteLanguage($favoriteLanguage);
            $dateOfBirth = !empty($_POST['dateOfBirth']) ? new DateTime($_POST['dateOfBirth']) : null;
            if (isset($dateOfBirth))    $student->setDateOfBirth($dateOfBirth->format('Y-m-d H:i:s'));

            $userObj = App::getRepository(UserRepository::class)->saveAndReturn($student, [
                "email" => $student->getEmail()
            ]);
            App::getRepository(StudentRepository::class)->save($userObj);
            FlashMessage::unset('username');
            FlashMessage::unset('email');

            $message = 'Student ' . $student->getUsername() . ' created';
            FlashMessage::set('message', $message);

            App::get('router')->redirect('login');
        } catch (ValidationException $validationException) {
            FlashMessage::set('register-error', [$validationException->getMessage()]);
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
