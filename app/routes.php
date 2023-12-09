<?php

$router->get ('', 'PagesController@index');
$router->get ('courses', 'PagesController@courses');
$router->get ('teachers', 'PagesController@teachers');
$router->get ('events', 'PagesController@events');
$router->get ('about', 'PagesController@about');
$router->get ('course-single', 'PagesController@courseSingle');
$router->get ('gallery', 'PagesController@gallery');
$router->get ('news', 'PagesController@news');
$router->get ('contact', 'PagesController@contact');