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
use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\repository\CoursePictureRepository;
use macchiato_academy\app\repository\CourseRepository;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\repository\LanguageRepository;
use macchiato_academy\app\repository\StudentJoinsCourseRepository;
use macchiato_academy\app\utils\File;

class CPController
{
    private array $sidebar = [
        "Register new user" => "/control-panel/register-new-user",
        "Manage users" => "/control-panel/manage-users",
        "Register new course" => "/control-panel/register-new-course",
        "Manage courses" => "/control-panel/manage-courses"
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

    public function deleteUser(int $id)
    {
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
                $repositoryNewRole = App::getRepository(UserRepository::class);
            } else if ($newRole === "ROLE_STUDENT") {
                $repositoryNewRole = App::getRepository(StudentRepository::class);
            } else if ($newRole === "ROLE_TEACHER") {
                $repositoryNewRole = App::getRepository(TeacherRepository::class);
            }

            $role = $user->getRole();
            if ($role === "ROLE_ADMIN") {
                $repositoryOldRole = App::getRepository(UserRepository::class);
            } else if ($role === "ROLE_STUDENT") {
                $repositoryOldRole = App::getRepository(StudentRepository::class);
            } else if ($role === "ROLE_TEACHER") {
                $repositoryOldRole = App::getRepository(TeacherRepository::class);
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

    public function newCourse()
    {
        $sidebar = $this->sidebar;
        $title = "CPanel - Register new course | Macchiato Academy";
        $errors = FlashMessage::get('register-error', []);
        $description = FlashMessage::get('description');
        $title = FlashMessage::get('title');
        $message = FlashMessage::get('message');
        $langSelected = FlashMessage::get('lang');
        $teacherAssigned = FlashMessage::get('teacher');

        $languages = App::getRepository(LanguageRepository::class)->findAll();
        $teachers = App::getRepository(TeacherRepository::class)->findAll();

        Response::renderView(
            'cpanel-new-course',
            compact(
                'title',
                'errors',
                'description',
                'title',
                'message',
                'sidebar',
                'languages',
                'langSelected',
                'teachers',
                'teacherAssigned'
            )
        );
    }

    public function validateCourseRegister()
    {
        try {
            if (!isset($_POST['title']) || empty($_POST['title']))
                throw new ValidationException('Title can\'t be empty');
            $title = htmlspecialchars(trim($_POST['title']));
            FlashMessage::set('title', $title);

            if (!isset($_POST['description']) || empty($_POST['description']))
                throw new ValidationException('Description can\'t be empty');
            $description = htmlspecialchars(trim($_POST['description']));
            FlashMessage::set('description', $description);

            if (!isset($_POST['language']) || empty($_POST['language']))
                throw new ValidationException('Language must be selected');
            $language = htmlspecialchars(trim($_POST['language']));
            FlashMessage::set('lang', $language);

            if (!isset($_POST['teacher']) || empty($_POST['teacher']))
                throw new ValidationException('Teacher must be selected');
            $teacher = htmlspecialchars(trim($_POST['teacher']));
            FlashMessage::set('teacher', $teacher);

            $typeFile = ['image/jpeg', 'image/png'];
            $pictureFile = new File('picture', $typeFile);
            $pictureFile->saveUploadFile(CoursePicture::COURSE_PICTURES_ROUTE);
            
            $image = new Image($pictureFile->getFileName());
            $imageObj = App::getRepository(ImageRepository::class)->saveAndReturn($image, [
                "name" => $image->getName()
            ]);

            $course = new Course();
            $course->setTitle($title)
                ->setDescription($description)
                ->setLanguage($language)
                ->setTeacher($teacher);
            $courseObj = App::getRepository(CourseRepository::class)->saveAndReturn($course, [
                "title" => $course->getTitle()
            ]);

            $coursePicture = new CoursePicture(
                $imageObj->getId(),
                $imageObj->getName(),
                $courseObj->getId()
            );

            App::getRepository(CoursePictureRepository::class)->save($coursePicture);

            $courseObj->setPicture($coursePicture->getId());

            App::getRepository(CourseRepository::class)->update($courseObj);

            FlashMessage::unset('title');
            FlashMessage::unset('description');
            FlashMessage::unset('teacher');
            FlashMessage::unset('lang');

            $message = "Course {$course->getTitle()} created";
            FlashMessage::set('message', $message);
        } catch (ValidationException $validationException) {
            FlashMessage::set('register-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('control-panel/register-new-course');
        }
    }

    public function manageCourses()
    {
        $title = "CPanel - Manage courses | Macchiato Academy";
        $sidebar = $this->sidebar;
        $errors = FlashMessage::get('manage-error', []);
        $message = FlashMessage::get('message');

        try {
            $courses = App::getRepository(CourseRepository::class)
                ->findAll();
            $languageRepository = App::getRepository(LanguageRepository::class);
            $teachers = App::getRepository(TeacherRepository::class)
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
            'cpanel-manage-courses',
            compact('title', 'sidebar', 'courses', 'errors', 'message', 'languageRepository', 'teachers')
        );
    }

    public function deleteCourse(int $id)
    {
        try {
            if (!isset($id) || empty($id))
                throw new ValidationException('ID course can\'t be empty');

            $courseRepository = App::getRepository(CourseRepository::class);
            $course = $courseRepository
                ->find($id);

            $students = App::getRepository(StudentRepository::class)
                ->findInCourse([
                    "id_course" => $course->getId()
                ]);
            foreach ($students as $student) {
                App::getRepository(StudentJoinsCourseRepository::class)
                    ->unsign($student, $course);
            }

            $courseRepository->delete([
                "id" => $course->getId()
            ]);

            FlashMessage::set('message', "Course with id $id deleted");
        } catch (AppException $appException) {
            FlashMessage::set('manage-error', [$appException->getMessage()]);
        } finally {
            App::get('router')->redirect('control-panel/manage-courses');
        }
    }
}
