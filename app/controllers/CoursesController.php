<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;
use macchiato_academy\app\repository\ProfilePictureRepository;
use macchiato_academy\app\repository\LanguageRepository;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\App;
use macchiato_academy\app\exceptions\ValidationException;
use macchiato_academy\app\utils\Utils;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\core\Security;
use DateTime;
use macchiato_academy\app\utils\File;
use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\repository\CoursePictureRepository;
use macchiato_academy\app\repository\CourseRepository;
use macchiato_academy\app\repository\TeacherRepository;
use macchiato_academy\app\entity\Course;
use macchiato_academy\app\entity\Teacher;
use macchiato_academy\app\exceptions\AppException;
use macchiato_academy\app\repository\StudentJoinsCourseRepository;

class CoursesController
{

    public function course(int $id)
    {
        $title = "Course | Macchiato Academy";
        $errors = FlashMessage::get('enroll-error', []);
        $message = FlashMessage::get('message');
        $coursePictureRepository = App::getRepository(CoursePictureRepository::class);
        $languageRepository = App::getRepository(LanguageRepository::class);
        $teacherRepository = App::getRepository(UserRepository::class);
        $courseRepository = App::getRepository(CourseRepository::class);
        $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);

        $course = $courseRepository->find($id);

        $coursePictureObj = App::getRepository(CoursePictureRepository::class)
            ->findInnerJoin(
                [
                    "image.id",
                    "coursepicture.id",
                    "id_course",
                    "name"
                ],
                "image",
                [
                    "coursepicture.id",
                    "image.id"
                ],
                [
                    "id_course" => $id
                ]
            );
        
        
        $language = null;
        if ($course->getLanguage())
            $language = $languageRepository->find($course->getLanguage())->getName();

        $teacher = $teacherRepository->find($course->getTeacher())->getUsername();

        $studentsEnrolled = $studentJoinsCourseRepository->countNumOfStudents($id);



        Response::renderView(
            'course-single',
            compact('title', 'course', 'coursePictureObj', 'language', 'studentsEnrolled', 'teacher', 'errors', 'message')
        );
    }

    public function enroll(int $id)
    {
        try {
            $user = App::get('appUser');
            if (!isset($user)) {
                throw new AppException("You must be logged in to enroll into a course");
            } 
            if ($user instanceof Teacher) {
                throw new ValidationException("Teachers can't enroll into a course");
            }
            
            
            $courseRepository = App::getRepository(CourseRepository::class);
            $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);
    
            $course = $courseRepository->find($id);

            if (Utils::isStudentEnrolled($user->getId(), $course->getId())) {
                throw new ValidationException("You have already enrolled this course");
            }
            $studentJoinsCourseRepository->sign($user->getId(), $course->getId());
            FlashMessage::set('message', "You have enrolled the course {$course->getTitle()} succesfully!");
            App::get('router')->redirect("profile/edit");
        } catch (AppException $appException) {
            FlashMessage::set('login-error', [$appException->getMessage()]);
            App::get('router')->redirect('login');
        } catch (ValidationException $validationException) {
            FlashMessage::set('enroll-error', [$validationException->getMessage()]);
            App::get('router')->redirect("course/$id");
        }

    }

    public function edit(?int $id = null)
    {
        $title = "Edit profile | Macchiato Academy";
        $errors = FlashMessage::get('edit-error', []);
        $message = FlashMessage::get('message');
        $languageRepository = App::getRepository(LanguageRepository::class);
        $languages = $languageRepository->findAll();

        if (isset($id)) {
            $user = App::getRepository(UserRepository::class)->find($id);
        } else {
            $user = App::get('appUser');
        }
        $username = $user->getUsername();
        $email = $user->getEmail();
        $dateOfBirth = $user->getDateOfBirth();
        $langSelected = $user->getFavoriteLanguage();
        $biography = $user->getBiography();
        if ($user->getProfilePicture() !== 1) {
            $pfpPreview = App::getRepository(ProfilePictureRepository::class)
                ->findInnerJoin(
                    [
                        "image.id",
                        "profilepicture.id",
                        "id_user",
                        "name",
                    ],
                    "image",
                    [
                        "profilepicture.id",
                        "image.id",
                    ],
                    [
                        "id_user" => $user->getId()
                    ]
                );
        } else {
            $pfpPreview = App::getRepository(ProfilePictureRepository::class)
                ->findInnerJoin(
                    [
                        "image.id",
                        "profilepicture.id",
                        "id_user",
                        "name",
                    ],
                    "image",
                    [
                        "profilepicture.id",
                        "image.id"
                    ],
                    [
                        "profilepicture__id" => "1"
                    ],
                );
        };

        Response::renderView(
            'edit-profile',
            compact('title', 'username', 'email', 'dateOfBirth', 'langSelected', 'languages', 'pfpPreview', 'biography', 'errors', 'message', 'id')
        );
    }

    public function validateUsername(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            if (!isset($_POST['username']) || empty($_POST['username']))
                throw new ValidationException('Username can\'t be empty');
            $username = htmlspecialchars(trim($_POST['username']));

            if ($user->getUsername() === $username)
                throw new ValidationException('Username is identical');

            $user->setUsername($username);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Username changed to $username");

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validateEmail(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            if (!isset($_POST['email']) || empty($_POST['email']))
                throw new ValidationException('Email can\'t be empty');
            $email = htmlspecialchars(trim($_POST['email']));

            if (!Utils::validateEmail($email))
                throw new ValidationException('Email format isn\'t correct');

            if ($user->getEmail() === $email)
                throw new ValidationException('Email is identical');

            $emailExists = App::getRepository(UserRepository::class)
                ->emailExists($email);
            if ($emailExists)
                throw new ValidationException('Email already exists');

            $user->setEmail($email);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Email changed to $email");

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validatePassword(?int $id = null)
    {
        try {
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

            $password = Security::encrypt($password);

            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }
            $user->setPassword($password);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Password has changed");

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validateProfilePicture(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            $typeFile = ['image/jpeg', 'image/png'];
            $pfpFile = new File('profilePicture', $typeFile);
            $pfpFile->saveUploadFile(CoursePicture::COURSE_PICTURES_ROUTE);
            
            $image = new Image($pfpFile->getFileName());
            $imageObj = App::getRepository(ImageRepository::class)->saveAndReturn($image, [
                "name" => $image->getName()
            ]);
            $profilePicture = new CoursePicture(
                $imageObj->getId(),
                $imageObj->getName(),
                $user->getId()
            );
            App::getRepository(ProfilePictureRepository::class)->updateProfilePicture($user, $profilePicture, $imageObj);
            FlashMessage::set('message', "Profile Picture updated");
        } catch (FileException $fileException) {
            FlashMessage::set('edit-error', [$fileException->getMessage()]);
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validateBirthday(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            $dateOfBirth = !empty($_POST['dateOfBirth']) ? new DateTime($_POST['dateOfBirth']) : null;
            if (isset($dateOfBirth))    $user->setDateOfBirth($dateOfBirth->format('Y-m-d H:i:s'));

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Birthday updated");

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validateFavoriteLanguage(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            $favoriteLanguage = empty($_POST['favoriteLanguage']) ? null : $_POST['favoriteLanguage'];

            $user->setFavoriteLanguage($favoriteLanguage);

            if (empty($favoriteLanguage)) {
                $message = "Favorite language deleted";
            } else {
                $favoriteLanguage = App::getRepository(LanguageRepository::class)->find($favoriteLanguage)->getName();
                $message = "Favorite language changed to $favoriteLanguage";
            }

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', $message);

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function validateBiography(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            $biography = htmlspecialchars(trim($_POST['biography']));
            $length = count(str_split($biography));

            if ($length > 500)
                throw new ValidationException("Biography max length is 500 characters");

            $user->setBiography($biography);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Biography updated");

        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }

    public function deleteFavoriteLanguage(?int $id = null)
    {
        if (isset($id)) {
            $user = App::getRepository(UserRepository::class)->find($id);
        } else {
            $user = App::get('appUser');
        }

        $user->setFavoriteLanguage(null);
        App::getRepository(UserRepository::class)->update($user);
        FlashMessage::set('message', "Favorite language deleted");

        App::get('router')->redirect('profile/edit/' . $id ?? '');
    }

    public function deleteBirthday(?int $id = null)
    {
        if (isset($id)) {
            $user = App::getRepository(UserRepository::class)->find($id);
        } else {
            $user = App::get('appUser');
        }

        $user->setDateOfBirth(null);
        App::getRepository(UserRepository::class)->update($user);
        FlashMessage::set('message', "Birthday deleted");

        App::get('router')->redirect('profile/edit/' . $id ?? '');
    }

    public function deleteProfilePicture(?int $id = null)
    {
        try {
            if (isset($id)) {
                $user = App::getRepository(UserRepository::class)->find($id);
            } else {
                $user = App::get('appUser');
            }

            if ($user->getProfilePicture() !== 1) {
                $profilePicture = App::getRepository(ProfilePictureRepository::class)
                    ->findInnerJoin(
                        [
                            "image.id",
                            "profilepicture.id",
                            "id_user",
                            "name",
                        ],
                        "image",
                        [
                            "profilepicture.id",
                            "image.id",
                        ],
                        [
                            "id_user" => $user->getId()
                        ]
                    );
            } else {
                //user has already no pfp
            };

            App::getRepository(ProfilePictureRepository::class)
                ->deleteProfilePicture($user, $profilePicture);

            FlashMessage::set('message', "Profile picture deleted");

        } catch (QueryException $queryException) {
            FlashMessage::set('edit-error', [$queryException->getMessage()]);
        } catch (FileException $fileException) {
            FlashMessage::set('edit-error', [$fileException->getMessage()]);
        } finally {
            App::get('router')->redirect('profile/edit/' . $id ?? '');
        }
    }
}
