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
                    <div class="col-md-8 testing probootstrap-animate" id="probootstrap-content" style="padding-left: 20px">
                        <h2>Manage users</h2>
                        <p>Register a student or a teacher.</p>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <table class="testing" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col" class="testing">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><?= $user->getId() ?></td>
                                        <td><?= $user->getUsername() ?></td>
                                        <td><?= $user->getEmail() ?></td>
                                        <td>
                                            <form action="/update-role/<?= $user->getId() ?>" method="post" class="probootstrap-form" style="display: inline;" novalidate>
                                                    <select name="role" class="form-control" style="display: inline; width: auto;">
                                                        <?php foreach ($roles as $role => $value) : ?>
                                                            <option value="<?= $role ?>" <?= ($role == $user->getRole()) ? 'selected' : '' ?>>
                                                                <?= explode("_", $role)[1] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button class="submit">
                                                <span style="color: LimeGreen">
                                                <i class="fa-solid fa-floppy-disk fa-lg"></i>
                                                </span></button>
                                            </form>
                                        </td>
                                        <td><a href="/profile/<?= $user->getId() ?>">
                                                <span style="color: DodgerBlue">
                                                    <i class="fa-solid fa-eye fa-lg"></i>
                                                </span>
                                            </a>
                                            <a href="/profile/edit/<?= $user->getId() ?>">
                                                <span style="color: Gold">
                                                    <i class="fa-solid fa-pen-to-square fa-lg"></i>
                                                </span></a>
                                            <a href="/delete-user/<?= $user->getId() ?>">
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