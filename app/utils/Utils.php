<?php

namespace macchiato_academy\app\utils;

use macchiato_academy\app\exceptions\QueryException;
use macchiato_academy\app\repository\StudentJoinsCourseRepository;
use macchiato_academy\app\repository\StudentRepository;
use macchiato_academy\core\App;
use PDO;

class Utils
{

    public static function isActive($uri): bool
    {
        return $_SERVER['REQUEST_URI'] === $uri;
    }

    public static function getOptions(): array
    {
        return [
            'Home' => '/',
            'Courses' => '/courses',
            'Teachers' => '/teachers',
            'Events' => '/events',
            'Pages' => [
                'About Us' => '/about',
                'Courses' => '/courses',
                'Course Single' => '/course-single',
                'Gallery' => '/gallery',
                'Sub Menu' => [
                    'Second Level Menu' => '#',
                ],
                'News' => '/news',
            ],
            'Contact' => '/contact',
        ];
    }

    public static function formatDate(?string $date): ?string
    {
        if (empty($date)) return null;
        return date("jS \of F \of Y", strtotime($date));
    }

    public static function validateEmail(string $email): bool {
        $sanitizedEmail = htmlspecialchars(trim($email));
        return filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL);
    }

    public static function printMenu($menu, $uri, $level = 1)
    {
        // foreach ($menu as $key => $value) {
        //     if (is_array($value)) {
        //         echo ($level == 1) ? '<li class="dropdown">' : '<li class="dropdown dropdown-submenu">';
        //         echo "<a href='#' data-toggle='dropdown' class='dropdown-toggle'>$key</a>";
        //         echo '<ul class="dropdown-menu">';
        //         self::printMenu($value, $uri, $level + 1);
        //         echo '</ul>';
        //     } else {
        //         echo ($uri === $value) ? '<li class ="active">' : '<li>';
        //         echo "<a href='$value'>$key</a>";
        //     }
        //     echo '</li>';
        // }
    }

    public static function isStudentEnrolled(int $id_student, int $id_course) {
        $studentJoinsCourseRepository = App::getRepository(StudentJoinsCourseRepository::class);
        $studentRepository = App::getRepository(StudentRepository::class);
        $whereClause = [
            "id_student" => $id_student,
            "id_course" => $id_course
        ];

        $exists = $studentJoinsCourseRepository->isStudentEnrolled($whereClause);

        return !empty($exists);
    }
}
