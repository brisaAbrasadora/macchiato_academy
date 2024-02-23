<?php

use macchiato_academy\app\utils\Utils;

$menu = Utils::getOptions();
$uri = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar navbar-default probootstrap-navbar">
    <div class="container">
        <div class="navbar-header">
            <div class="btn-more js-btn-more visible-xs">
                <a href="#"><i class="icon-dots-three-vertical "></i></a>
            </div>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="" title="ProBootstrap:Enlight">Enlight</a>
        </div>

        <div id="navbar-collapse" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="<?= Utils::isActive('/') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/') ? '#' : '/' ?>">Home</a>
                </li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">The Academy</a>
                    <ul class="dropdown-menu">
                        <li class="<?= Utils::isActive('/about') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/about') ? '#' : '/about' ?>">About us</a>
                        </li>
                        <li class="<?= Utils::isActive('/courses') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/courses') ? '#' : '/courses' ?>">Courses</a>
                        </li>
                        <li class="<?= Utils::isActive('/teachers') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/teachers') ? '#' : '/teachers' ?>">Teachers</a>
                        </li>
                    </ul>
                </li>
                <li class="<?= Utils::isActive('/news') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/news') ? '#' : '/news' ?>">News</a>
                </li>
                <li class="<?= Utils::isActive('/contact') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/contact') ? '#' : '/contact' ?>">Contact</a>
                </li>
                <?php if (is_null($app['user'])) : ?>
                    <li class="<?= Utils::isActive('/sign-up') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/sign-up') ? '#' : '/sign-up' ?>">Sign up</a>
                </li>
                <li class="<?= Utils::isActive('/login') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/login') ? '#' : '/login' ?>">Login</a>
                </li>
                <?php else : ?>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle"><?= $app['user']->getUsername() ?></a>
                    <ul class="dropdown-menu">
                        <li class="<?= Utils::isActive('/profile') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/profile') ? '#' : '/profile' ?>">Go to profile</a>
                        </li>
                        <li class="<?= Utils::isActive('/profile') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/profile') ? '#' : '/' ?>">Register teacher</a>
                        </li>
                        <li class="<?= Utils::isActive('/profile') ? 'active' : '' ?>">
                            <a href="<?= Utils::isActive('/profile') ? '#' : '/' ?>">Register</a>
                        </li>
                    </ul>
                </li>
                <li class="<?= Utils::isActive('/logout') ? 'active' : '' ?>">
                    <a href="<?= Utils::isActive('/logout') ? '#' : '/logout' ?>">Logout</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>