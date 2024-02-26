<?php

namespace macchiato_academy\app\utils;

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
}
