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

class PagesController
{
    public function index()
    {
        $title = "Home | Macchiato Academy";

        Response::renderView(
            'index',
            compact('title')
        );
    }

    public function about()
    {
        $title = "About | Macchiato Academy";

        Response::renderView(
            'about',
            compact('title')
        );
    }

    public function courses()
    {
        $title = "Courses | Macchiato Academy";

        Response::renderView(
            'courses',
            compact('title')
        );
    }

    public function courseSingle()
    {
        $title = "NO INCLUDE | Macchiato Academy";

        Response::renderView(
            'course-single',
            compact('title')
        );
    }

    public function events()
    {
        $title = "Events | Macchiato Academy";

        Response::renderView(
            'events',
            compact('title')
        );
    }

    public function gallery()
    {
        $title = "Gallery | Macchiato Academy";

        Response::renderView(
            'gallery',
            compact('title')
        );
    }

    public function news()
    {
        $title = "News | Macchiato Academy";

        Response::renderView(
            'news',
            compact('title')
        );
    }

    public function teachers()
    {
        $title = "Teachers | Macchiato Academy";

        Response::renderView(
            'teachers',
            compact('title')
        );
    }

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
            // En la tabla ProfilePicture, el id 1 tiene un id_user null porque es la imagen por defecto.
            // Entonces, si es diferente a null, contendría el id del usuario, y habría que buscar en
            // la tabla ProfilePicture, con el id del usuario, el id de la imagen alojada en la tabla image
        } else {
            $profilePicture = $profilePictureRepository->findBy([
                "id" => 1
            ]);
        }

        $profilePictureObject = $profilePictureRepository->findInnerJoin(
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
                "profilepicture_id" => "1"
            ],
        );

        $favoriteLanguage = null;
        if ($user->getFavoriteLanguage())
            $favoriteLanguage = $languageRepository->find($user->getFavoriteLanguage())->getName();

        Response::renderView(
            'profile',
            compact('title', 'user', 'profilePictureObject', 'favoriteLanguage')
        );
    }
}
