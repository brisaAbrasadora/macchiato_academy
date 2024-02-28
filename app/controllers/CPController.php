<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\exceptions\LanguageException;
use macchiato_academy\app\repository\LanguageRepository;
use macchiato_academy\app\repository\ProfilePictureRepository;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\App;
use macchiato_academy\core\Response;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\utils\Utils;
use macchiato_academy\app\entity\Teacher;
use macchiato_academy\app\entity\Student;
use macchiato_academy\app\repository\StudentRepository;
use macchiato_academy\app\repository\TeacherRepository;
use macchiato_academy\core\Security;
use DateTime;

class CPController
{
    public function newUser()
    {
        $title = "CPanel - Register new user | Macchiato Academy";
        $errors = FlashMessage::get('register-error', []);
        $email = FlashMessage::get('email');
        $username = FlashMessage::get('username');
        $message = FlashMessage::get('message');

        Response::renderView(
            'cpanel-new-user',
            compact('title', 'errors', 'email', 'username', 'message')
        );
    }

    public function validateUserRegister()
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
                throw new ValidationException('Email already exists');
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

            $dateOfJoin = new DateTime();
            $password = Security::encrypt($password);

            if ($_POST['type'] === "student") {
                $user = new Student();
            } else {
                $user = new Teacher();
            }

            $userType = $_POST['type'];
            $user->setUsername($username)
                ->setEmail($email)
                ->setPassword($password)
                ->setDateOfJoin($dateOfJoin->format('Y-m-d H:i:s'));

            $userObj = App::getRepository(UserRepository::class)->saveAndReturn($user, [
                "email" => $user->getEmail()
            ]);

            if ($userType === "student") {
                App::getRepository(StudentRepository::class)->save($userObj);
            } else {
                App::getRepository(TeacherRepository::class)->save($userObj);
            }

            FlashMessage::unset('username');
            FlashMessage::unset('email');

            $message = ucfirst($userType) . " {$user->getUsername()} created";
            FlashMessage::set('message', $message);

            App::get('router')->redirect('control-panel/register-new-user');
        } catch (ValidationException $validationException) {
            FlashMessage::set('register-error', [$validationException->getMessage()]);
            App::get('router')->redirect('control-panel/register-new-user');
        }
    }

    public function testing()
    {
        $title = "Testing";
        $isset = isset($_FILES['profilePicture']);
        $obj = $_FILES;
        $empty = empty($_POST['profilePicture']);

        Response::renderView(
            'testing',
            compact('title', 'isset', 'obj', 'empty')
        );
    }
}
