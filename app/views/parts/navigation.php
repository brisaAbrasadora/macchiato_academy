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
                <?php
                $menu = Utils::getOptions();
                Utils::printMenu($menu, $uri); ?>
            </ul>
        </div>
    </div>
</nav>