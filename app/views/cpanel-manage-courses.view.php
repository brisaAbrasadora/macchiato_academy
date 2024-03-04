<section class="probootstrap-section probootstrap-section-colored">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left section-heading probootstrap-animate">
                <h1 class="mb0">Control Panel</h1>
            </div>
        </div>
    </div>
</section>

<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <?php include __DIR__ . '/parts/sidebar.php'; ?>
                    <div class="col-md-8 probootstrap-animate" id="probootstrap-content" style="padding-left: 20px">
                        <h2>Manage courses</h2>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <table class="courses">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Language</th>
                                    <th scope="col">Teacher</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courses as $course) : ?>
                                    <tr>
                                        <td><?= $course->getId() ?></td>
                                        <td><?= $course->getTitle() ?></td>
                                        <td><?= $languageRepository
                                                ->find($course->getLanguage())
                                                ->getName() ?></td>
                                        <td>
                                            <form action="/update-teacher/<?= $course->getId() ?>" method="post" class="probootstrap-form" style="display: inline;" novalidate>
                                                <select name="role" class="form-control" style="display: inline; width: auto;">
                                                    <?php foreach ($teachers as $teacher) : ?>
                                                        <option value="<?= $teacher->getId() ?>" <?= ($teacher->getId() == $course->getTeacher()) ? 'selected' : '' ?>>
                                                            <?= $teacher->getUsername() ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="submit">
                                                    <span style="color: LimeGreen">
                                                        <i class="fa-solid fa-floppy-disk fa-lg"></i>
                                                    </span></button>
                                            </form>
                                        </td>
                                        <td><a href="/course/<?= $course->getId() ?>">
                                                <span style="color: DodgerBlue">
                                                    <i class="fa-solid fa-eye fa-lg"></i>
                                                </span>
                                            </a>
                                            <a href="/course/edit/<?= $course->getId() ?>">
                                                <span style="color: Gold">
                                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                                </span></a>
                                            <a href="/delete-course/<?= $course->getId() ?>">
                                                <span style="color: Tomato">
                                                    <i class="fa-solid fa-xmark fa-xl"></i>
                                                </span>
                                            </a>
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
</section>