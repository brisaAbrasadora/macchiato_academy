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
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\exceptions\FileException;
use macchiato_academy\app\exceptions\QueryException;

class ProfileController
{

    public function profile(?int $id = null)
    {
        $title = "Profile | Macchiato Academy";
        $profilePictureRepository = App::getRepository(ProfilePictureRepository::class);
        $languageRepository = App::getRepository(LanguageRepository::class);
        $userRepository = App::getRepository(UserRepository::class);
        if (isset($id)) {
            $user = $userRepository->find($id);
        } else {
            $user = App::get('appUser');
        }

        if ($user->getProfilePicture() !== 1) {
            $profilePictureObject = App::getRepository(ProfilePictureRepository::class)
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
            $profilePictureObject = App::getRepository(ProfilePictureRepository::class)
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

        $favoriteLanguage = null;
        if ($user->getFavoriteLanguage())
            $favoriteLanguage = $languageRepository->find($user->getFavoriteLanguage())->getName();

        Response::renderView(
            'profile',
            compact('title', 'user', 'profilePictureObject', 'favoriteLanguage')
        );
    }

    public function edit()
    {
        $title = "Edit profile | Macchiato Academy";
        $errors = FlashMessage::get('edit-error', []);
        $message = FlashMessage::get('message');
        $languageRepository = App::getRepository(LanguageRepository::class);
        $languages = $languageRepository->findAll();

        $user = App::get('appUser');
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
            compact('title', 'username', 'email', 'dateOfBirth', 'langSelected', 'languages', 'pfpPreview', 'biography', 'errors', 'message')
        );
    }

    public function validateUsername()
    {
        try {
            $user = App::get('appUser');

            if (!isset($_POST['username']) || empty($_POST['username']))
                throw new ValidationException('Username can\'t be empty');
            $username = htmlspecialchars(trim($_POST['username']));

            if ($user->getUsername() === $username)
                throw new ValidationException('Username is identical');

            $user->setUsername($username);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Username changed to $username");

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validateEmail()
    {
        try {
            $user = App::get('appUser');

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

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validatePassword()
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

            $user = App::get('appUser');
            $user->setPassword($password);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Password has changed");

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validateProfilePicture()
    {
        try {
            $user = App::get('appUser');

            $typeFile = ['image/jpeg', 'image/png'];
            $pfpFile = new File('profilePicture', $typeFile);
            $pfpFile->saveUploadFile(ProfilePicture::PROFILE_PICTURES_ROUTE);
            
            $image = new Image($pfpFile->getFileName());
            $imageObj = App::getRepository(ImageRepository::class)->saveAndReturn($image, [
                "name" => $image->getName()
            ]);
            $profilePicture = new ProfilePicture(
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
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validateBirthday()
    {
        try {
            $user = App::get('appUser');

            $dateOfBirth = !empty($_POST['dateOfBirth']) ? new DateTime($_POST['dateOfBirth']) : null;
            if (isset($dateOfBirth))    $user->setDateOfBirth($dateOfBirth->format('Y-m-d H:i:s'));

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Birthday updated");

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validateFavoriteLanguage()
    {
        try {
            $user = App::get('appUser');

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

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function validateBiography()
    {
        try {
            $user = App::get('appUser');

            $biography = htmlspecialchars(trim($_POST['biography']));
            $length = count(str_split($biography));

            if ($length > 500)
                throw new ValidationException("Biography max length is 500 characters");

            $user->setBiography($biography);

            App::getRepository(UserRepository::class)->update($user);
            FlashMessage::set('message', "Biography updated");

            App::get('router')->redirect('profile/edit');
        } catch (ValidationException $validationException) {
            FlashMessage::set('edit-error', [$validationException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }

    public function deleteFavoriteLanguage()
    {
        $user = App::get('appUser');

        $user->setFavoriteLanguage(null);
        App::getRepository(UserRepository::class)->update($user);
        FlashMessage::set('message', "Favorite language deleted");

        App::get('router')->redirect('profile/edit');
    }

    public function deleteBirthday()
    {
        $user = App::get('appUser');

        $user->setDateOfBirth(null);
        App::getRepository(UserRepository::class)->update($user);
        FlashMessage::set('message', "Birthday deleted");

        App::get('router')->redirect('profile/edit');
    }

    public function deleteProfilePicture()
    {
        try {
            $user = App::get('appUser');

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

            App::get('router')->redirect('profile/edit');
        } catch (QueryException $queryException) {
            FlashMessage::set('edit-error', [$queryException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        } catch (FileException $fileException) {
            FlashMessage::set('edit-error', [$fileException->getMessage()]);
            App::get('router')->redirect('profile/edit');
        }
    }
}
