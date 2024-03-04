<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left section-heading probootstrap-animate">
                <h1 class="mb0">Control Panel</h1>
            </div>
        </div>
    </div>
</section>
<?php
var_dump($teachers);
?>
<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <?php include __DIR__ . '/parts/sidebar.php'; ?>
                    <div class="col-md-7 col-md-push-1 probootstrap-animate" id="probootstrap-content">
                        <h2>Register new course</h2>
                        <p>Register a course.</p>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <form action="/validate-course-register" method="post" class="probootstrap-form" novalidate>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" value="<?= $title ?? '' ?>" />
                            </div>
                            <div class="form-group">
                                <label for="email">Description</label>
                                <input type="text" class="form-control" value="<?= $description ?? '' ?>" name="description" />
                            </div>
                            <div class="form-group">
                                <label for="language">Language</label>
                                <select name="language" class="form-control">
                                    <option value="" <?= (!isset($langSelected)) ? 'selected' : '' ?>>---</option>
                                    <?php foreach ($languages as $lang) : ?>
                                        <option value="<?= $lang->getId() ?>" <?= ($langSelected == $lang->getId()) ? 'selected' : '' ?>>
                                            <?= $lang->getName() ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="teacher">Teacher</label>
                                <select name="teacher" class="form-control">
                                    <option value="" <?= (!isset($teacherAssigned)) ? 'selected' : '' ?>>---</option>
                                    <?php foreach ($teachers as $teacher) : ?>
                                        <option value="<?= $teacher->getId() ?>" <?= ($teacherAssigned == $teacher->getId()) ? 'selected' : '' ?>>
                                            <?= $teacher->getUsername() ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-primary btn-lg">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>