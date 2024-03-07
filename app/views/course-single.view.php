<?php

use macchiato_academy\app\utils\Utils;
use macchiato_academy\core\App;

?>
<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left section-heading probootstrap-animate">
                <h1><?= $course->getTitle() ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-4" id="probootstrap-sidebar">
                        <div class="probootstrap-sidebar-inner probootstrap-overlap probootstrap-animate">
                            <h3>Details</h3>
                            <ul class="probootstrap-side-menu">
                                <li><span class="bold">Language</span>: <?= $language ?></li>
                                <li><span class="bold">Teacher</span>: <?= $teacher ?></li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7 col-md-push-1 probootstrap-animate" id="probootstrap-content">
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <h2>Description</h2>
                        <p>
                            <?= $course->getDescription() ?>
                        </p>
                        <p>
                            <?php if (Utils::isStudentEnrolled($app['user']->getId(), $course->getId())) { ?>
                        <form action="/unsign-course/<?= $course->getId() ?>" method="post">
                            <button type="submit" class="btn btn-danger">
                                Unsing of this course
                            </button>
                        </form>
                            <?php } else { ?>
                        <form action="/enroll-course/<?= $course->getId() ?>" method="post">
                            <button type="submit" class="btn btn-primary">
                                Enroll with this course now
                            </button>
                        </form>
                    <?php } ?>
                    <span class="enrolled-count"><?= $studentsEnrolled ?> students enrolled</span>
                    </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>