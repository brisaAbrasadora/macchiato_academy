<?php

namespace macchiato_academy\app\controllers;

use macchiato_academy\app\entity\CoursePicture;
use macchiato_academy\app\repository\ImageRepository;
use macchiato_academy\app\entity\Image;
use macchiato_academy\app\entity\ProfilePicture;
use macchiato_academy\app\exceptions\LanguageException;
use macchiato_academy\app\repository\LanguageRepository;
use macchiato_academy\app\repository\ProfilePictureRepository;
use macchiato_academy\app\repository\CoursePictureRepository;
use macchiato_academy\app\repository\TeacherRepository;
use macchiato_academy\app\repository\UserRepository;
use macchiato_academy\core\App;
use macchiato_academy\core\Response;
use macchiato_academy\core\helpers\FlashMessage;
use macchiato_academy\app\entity\Teacher;
use macchiato_academy\app\repository\CourseRepository;
use macchiato_academy\app\repository\StudentJoinsCourseRepository;

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
        $courses = App::getRepository(CourseRepository::class)->findAll();
        $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);
        
        $coursePictureIds = array_map(
            function ($coursePicture) {
                return $coursePicture->getId();
            },
            App::getRepository(CoursePictureRepository::class)->findAll()
        );
        $coursePicture = new CoursePicture();
        $imageKeys = array_map(
            function ($key): string {
                return "image.$key";
            },
            array_keys((new Image())->toArray())
        );
        array_push($imageKeys, "coursePicture.id", "coursePicture.id_course");

        $coursePicturesObj = [];
        foreach ($coursePictureIds as $coursePictureId) {
            $coursePicture = App::getRepository(CoursePictureRepository::class)
                ->findInnerJoin(
                    $imageKeys,
                    "image",
                    [
                        "image.id",
                        "coursePicture.id"
                    ],
                    [
                        "coursePicture__id" => $coursePictureId
                    ]
                );
            array_push($coursePicturesObj, $coursePicture);
        }

        $coursePictures = [];
        foreach ($coursePicturesObj as $coursePicture) {
            $coursePictures[$coursePicture->getIdCourse()] = $coursePicture;
        }

        $languagesObj = App::getRepository(LanguageRepository::class)->findAll();

        $languages = [];
        foreach ($languagesObj as $key => $value) {
            $key = $value->getId();
            $languages[$key] = $value;
        }

        Response::renderView(
            'courses',
            compact('title', 'courses', 'coursePictures', 'languages', 'studentJoinsCourseRepository')
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
        $teacherIds = array_map(
            function ($teacher) {
                return $teacher->getId();
            },
            App::getRepository(TeacherRepository::class)->findAll()
        );
        $teacher = new Teacher();
        $userKeys = array_map(fn ($key): string => "user.$key", array_keys($teacher->toArray()));
        array_push($userKeys, "teacher.id");

        $teachers = [];
        foreach ($teacherIds as $teacherId) {
            $teacher = App::getRepository(TeacherRepository::class)->findInnerJoin(
                $userKeys,
                "user",
                [
                    "user.id",
                    "teacher.id"
                ],
                [
                    "teacher__id" => $teacherId
                ]
            );
            array_push($teachers, $teacher);
        }

        $profilePicturesObj = [];
        $profilePictureIds = array_map(
            function ($profilePicture) {
                return $profilePicture->getId();
            },
            App::getRepository(ProfilePictureRepository::class)->findAll()
        );
        $profilePicture = new ProfilePicture();
        $imageKeys = array_map(
            function ($key): string {
                return "image.$key";
            },
            array_keys((new Image())->toArray())
        );
        array_push($imageKeys, "profilePicture.id", "profilePicture.id_user");

        $profilePicturesObj = [];
        foreach ($profilePictureIds as $profilePictureId) {
            $profilePicture = App::getRepository(ProfilePictureRepository::class)
                ->findInnerJoin(
                    $imageKeys,
                    "image",
                    [
                        "image.id",
                        "profilePicture.id"
                    ],
                    [
                        "profilePicture__id" => $profilePictureId
                    ]
                );
            array_push($profilePicturesObj, $profilePicture);
        }

        $pfps = [];
        foreach ($profilePicturesObj as $profilePicture) {
            $pfps[$profilePicture->getIdUser()] = $profilePicture;
        }

        $languagesObj = App::getRepository(LanguageRepository::class)->findAll();

        $languages = [];
        foreach ($languagesObj as $key => $value) {
            $key = $value->getId();
            $languages[$key] = $value;
        }

        Response::renderView(
            'teachers',
            compact('title', 'teachers', 'pfps', 'languages')
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
