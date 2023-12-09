<?php
namespace macchiato_academy\app\controllers;

use macchiato_academy\core\Response;

class PagesController {
    public function index() {
        $title = "Home | Macchiato Academy";

        Response::renderView(
            'index',
            compact('title')
        );
    }

    public function about() {
        $title = "About | Macchiato Academy";

        Response::renderView(
            'about',
            compact('title')
        );
    }

    public function courses() {
        $title = "Courses | Macchiato Academy";

        Response::renderView(
            'courses',
            compact('title')
        );
    }

    public function courseSingle() {
        $title = "NO INCLUDE | Macchiato Academy";

        Response::renderView(
            'course-single',
            compact('title')
        );
    }

    public function events() {
        $title = "Events | Macchiato Academy";

        Response::renderView(
            'events',
            compact('title')
        );
    }

    public function gallery() {
        $title = "Gallery | Macchiato Academy";

        Response::renderView(
            'gallery',
            compact('title')
        );
    }

    public function news() {
        $title = "News | Macchiato Academy";

        Response::renderView(
            'news',
            compact('title')
        );
    }

    public function teachers() {
        $title = "Teachers | Macchiato Academy";

        Response::renderView(
            'teachers',
            compact('title')
        );
    }
}