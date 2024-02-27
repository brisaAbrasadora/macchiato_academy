<?php

$router->get ('', 'PagesController@index');
$router->get ('courses', 'PagesController@courses', 'ROLE_STUDENT');
$router->get ('teachers', 'PagesController@teachers');
$router->get ('events', 'PagesController@events');
$router->get ('about', 'PagesController@about');
$router->get ('course-single', 'PagesController@courseSingle');
$router->get ('gallery', 'PagesController@gallery');
$router->get ('news', 'PagesController@news');
$router->get ('contact', 'PagesController@contact');
$router->get ('login', 'AuthController@login');
$router->get ('sign-up', 'AuthController@registerStudent');
$router->get ('logout', 'AuthController@logout');
$router->get ('profile', 'PagesController@profile');
$router->get ('profile/:id', 'PagesController@profile');

$router->post ('check-login', 'AuthController@checkLogin');
$router->post ('check-register', 'AuthController@checkRegisterStudent');