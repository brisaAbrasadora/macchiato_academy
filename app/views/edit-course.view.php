<?php

?>
<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-12" id="probootstrap-sidebar">
                        <h2>Edit course #<?= $course->getId() ?></h2>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-title/<?= $id ?>" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" name="title" value="<?= $titleCourse ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-5 border">
                                <form action="/validate-description/<?= $id ?>" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" name="description" value="<?= $description ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-teacher/<?= $id ?>" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="teacher">Teacher</label>

                                        <select name="teacher" class="form-control">
                                            <?php foreach ($teachers as $teach) : ?>
                                                <option value="<?= $teach->getId() ?>" <?= ($teacher == $teach->getId()) ? 'selected' : '' ?>>
                                                    <?= $teach->getUsername() ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-5 border">
                                <form action="/validate-language/<?= $id ?>" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="language">Language</label>
                                        <select name="language" class="form-control">
                                            <?php foreach ($languages as $lang) : ?>
                                                <option value="<?= $lang->getId() ?>" <?= ($language == $lang->getId()) ? 'selected' : '' ?>>
                                                    <?= $lang->getName() ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-course-picture/<?= $id ?>" method="post" class="probootstrap-form" enctype="multipart/form-data" novalidate>
                                    <div class="form-group">
                                        <label for="coursePicture">Course Picture</label>
                                        <input type="file" class="form-control-file" name="coursePicture" />
                                    </div>
                                    <div>
                                        <img src="<?= $currentPicture->getCoursePicturesPath() ?>" alt="">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-5 students border">
                                <label for="students">Students</label>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student) : ?>
                                            <tr>
                                                <td><?= $student->getId() ?></td>
                                                <td><?= $student->getUsername() ?></td>
                                                <td><?= $student->getEmail() ?></td>
                                                <td>
                                                    <a href="/profile/<?= $student->getId() ?>">
                                                        <span style="color: DodgerBlue">
                                                            <i class="fa-solid fa-eye fa-lg"></i>
                                                        </span>
                                                    </a>
                                                    <form action="/unsign" method="post" style="display: inline;">
                                                        <input type="hidden" name="course" value="<?= $course->getId() ?>">
                                                        <input type="hidden" name="student" value="<?= $student->getId() ?>">
                                                        <button class="submit" type="submit">
                                                            <span style="color: Tomato">
                                                                <i class="fa-solid fa-xmark fa-xl"></i>
                                                            </span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>