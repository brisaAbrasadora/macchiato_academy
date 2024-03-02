<?php

namespace macchiato_academy\app\controllers;

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
use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\app\exceptions\QueryException;
use Exception;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\repository\LanguageRepository;

class CPController
{
    private array $sidebar = [
        "Register new user" => "/control-panel/register-new-user",
        "Manage users" => "/control-panel/manage-users",
    ];

    public function newUser()
    {
        $sidebar = $this->sidebar;
        $title = "CPanel - Register new user | Macchiato Academy";
        $errors = FlashMessage::get('register-error', []);
        $email = FlashMessage::get('email');
        $username = FlashMessage::get('username');
        $message = FlashMessage::get('message');

        Response::renderView(
            'cpanel-new-user',
            compact('title', 'errors', 'email', 'username', 'message', 'sidebar')
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

    public function manageUsers()
    {
        $title = "CPanel - Manage users | Macchiato Academy";
        $sidebar = $this->sidebar;
        $errors = FlashMessage::get('manage-error', []);
        $message = FlashMessage::get('message');
        $roles = App::get('config')['security']['roles'];
        array_pop($roles);



        try {
            $users = App::getRepository(UserRepository::class)
                ->findAll();
        } catch (QueryException $queryException) {
            FlashMessage::set('errors', [$queryException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('errors', [$appException->getMessage()]);
        } catch (Exception $exception) {
            FlashMessage::set('errors', [$exception->getMessage()]);
        }

        FlashMessage::unset('manage-error');
        FlashMessage::unset('message');

        Response::renderView(
            'cpanel-manage-users',
            compact('title', 'sidebar', 'users', 'errors', 'message', 'roles')
        );
    }

    public function deleteUser(int $id) {
        try {
            if ($id === 1) {
                throw new AppException("The admin of this site can't be deleted");
            } else {
                App::getRepository(UserRepository::class)->deleteUser(
                    App::getRepository(UserRepository::class)->find($id)
                );
            }
            FlashMessage::set('message', "User with id $id deleted");
        } catch (FileException $fileException) {
            FlashMessage::set('manage-error', [$fileException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('manage-error', [$appException->getMessage()]);
        } finally {
            App::get('router')->redirect('control-panel/manage-users');
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

    public function updateRole(int $id)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                throw new ValidationException("The ID must be set");
            }

            if ($id === 1) {
                throw new AppException("The administrator cannot change their role");
            }

            if (!isset($_POST['role']) || empty($_POST['role']))
                throw new ValidationException('Role can\'t be empty');

            $newRole = htmlspecialchars(trim($_POST['role']));
            if ($newRole === "ROLE_ADMIN") {
                $repositoryNewRole = App::getRepository(UserRepository::class) ;
            } else if ($newRole === "ROLE_STUDENT") {
                $repositoryNewRole = App::getRepository(StudentRepository::class) ;
            } else if ($newRole === "ROLE_TEACHER") {
                $repositoryNewRole = App::getRepository(TeacherRepository::class) ;
            }

            $role = $user->getRole();
            if ($role === "ROLE_ADMIN") {
                $repositoryOldRole = App::getRepository(UserRepository::class) ;
            } else if ($role === "ROLE_STUDENT") {
                $repositoryOldRole = App::getRepository(StudentRepository::class) ;
            } else if ($role === "ROLE_TEACHER") {
                $repositoryOldRole = App::getRepository(TeacherRepository::class) ;
            }

            if ($user->getRole() === $newRole)
                throw new ValidationException("{$user->getUsername()} is already that role");

            if ($role === "ROLE_ADMIN") {
                $repositoryNewRole->insert([
                    "id" => $user->getId()
                ]);
            } else {
                if ($newRole === "ROLE_ADMIN") {
                    $repositoryOldRole->delete([
                        "id" => $user->getId()
                    ]);
                } else {
                    $repositoryOldRole->delete([
                        "id" => $user->getId()
                    ]);
        
                    $repositoryNewRole->insert([
                        "id" => $user->getId()
                    ]);
                }
            }

            $user->setRole($newRole);

            App::getRepository(UserRepository::class)->update($user);


            $newRole = explode("_", $newRole)[1];
            FlashMessage::set('message', "{$user->getUsername()} is now a $newRole");

        } catch (ValidationException $validationException) {
            FlashMessage::set('manage-error', [$validationException->getMessage()]);
        } catch (AppException $appException) {
            FlashMessage::set('manage-error', [$appException->getMessage()]);
        } finally {
            App::get('router')->redirect('control-panel/manage-users');
        }
    }
}
