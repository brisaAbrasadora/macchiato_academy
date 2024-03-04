<?php

use macchiato_academy\app\controllers\CPController;

$router->get ('', 'PagesController@index');
$router->get ('courses', 'PagesController@courses');
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
$router->get ('profile', 'ProfileController@profile');
$router->get ('profile/edit', 'ProfileController@edit');
$router->get ('profile/edit/:id', 'ProfileController@edit', 'ROLE_ADMIN');
$router->get ('profile/:id', 'ProfileController@profile');

$router->get ('course/:id', 'CoursesController@course');

$router->get ('control-panel/register-new-user', 'CPController@newUser', 'ROLE_ADMIN');
$router->get ('control-panel/manage-users', 'CPController@manageUsers', 'ROLE_ADMIN');
$router->get ('control-panel/register-new-course', 'CPController@newCourse', 'ROLE_ADMIN');
$router->get ('control-panel/manage-courses', 'CPController@manageCourses', 'ROLE_ADMIN');

$router->post ('check-login', 'AuthController@checkLogin');
$router->post ('validate-student-register', 'AuthController@validateStudentRegister');

$router->post ('validate-username/:id', 'ProfileController@validateUsername', 'ROLE_ADMIN');
$router->post ('validate-username', 'ProfileController@validateUsername');
$router->post ('validate-email', 'ProfileController@validateEmail');
$router->post ('validate-email/:id', 'ProfileController@validateEmail', 'ROLE_ADMIN');
$router->post ('validate-birthday', 'ProfileController@validateBirthday');
$router->post ('validate-birthday/:id', 'ProfileController@validateBirthday', 'ROLE_ADMIN');
$router->post ('validate-profile-picture', 'ProfileController@validateProfilePicture');
$router->post ('validate-profile-picture/:id', 'ProfileController@validateProfilePicture', 'ROLE_ADMIN');
$router->post ('validate-favorite-language', 'ProfileController@validateFavoriteLanguage');
$router->post ('validate-favorite-language/:id', 'ProfileController@validateFavoriteLanguage', 'ROLE_ADMIN');
$router->post ('validate-biography', 'ProfileController@validateBiography');
$router->post ('validate-biography/:id', 'ProfileController@validateBiography', 'ROLE_ADMIN');

$router->post ('delete-birthday', 'ProfileController@deleteBirthday');
$router->post ('delete-birthday/:id', 'ProfileController@deleteBirthday', 'ROLE_ADMIN');
$router->post ('delete-profile-picture', 'ProfileController@deleteProfilePicture');
$router->post ('delete-profile-picture/:id', 'ProfileController@deleteProfilePicture', 'ROLE_ADMIN');
$router->post ('delete-favorite-language', 'ProfileController@deleteFavoriteLanguage');
$router->post ('delete-favorite-language/:id', 'ProfileController@deleteFavoriteLanguage', 'ROLE_ADMIN');

$router->post ('validate-user-register', 'CPController@validateUserRegister', 'ROLE_ADMIN');
$router->get ('delete-user/:id', 'CPController@deleteUser', 'ROLE_ADMIN');
$router->post ('update-role/:id', 'CPController@updateRole', 'ROLE_ADMIN');
$router->post ('validate-course-register', 'CPController@validateCourseRegister', 'ROLE_ADMIN');
$router->post ('update-teacher/:id', 'CPController@updateTeacher', 'ROLE_ADMIN');
$router->get ('delete-course/:id', 'CPController@deleteCourse', 'ROLE_ADMIN');

$router->post ('testing', 'PagesController@testing');