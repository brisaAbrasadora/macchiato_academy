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

    public function contact()
    {
        $title = "Contact | Macchiato Academy";

        Response::renderView(
            'contact',
            compact('title')
        );
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
