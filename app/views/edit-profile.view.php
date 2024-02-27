<?php

?>
<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-6" id="probootstrap-sidebar">
                        <h2>Edit your profile</h2>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="/validate-username" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" value="<?= $username ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit username</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="/validate-email" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" value="<?= $email ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit email</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="/validate-password" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" />
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm password</label>
                                        <input type="password" class="form-control" name="confirmPassword" />
                                    </div>
                                    <button class="btn btn-primary">Edit password</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="/validate-profile-picture" method="post" class="probootstrap-form" enctype="multipart/form-data" novalidate>
                                    <div class="form-group">
                                        <label for="profilePicture">Profile Picture</label>
                                        <input type="file" class="form-control-file" name="profilePicture" />
                                    </div>
                                    <div>
                                        <img src="<?= $pfpPreview->getProfilePicturesPath() ?>" alt="" width="100px">
                                    </div>
                                    <button class="btn btn-primary">Edit profile picture</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="/validate-birthday" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="dateOfBirth">Date of Birth</label>
                                        <input type="date" class="form-control " name="dateOfBirth" value="<?= $dateOfBirth ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit birthday</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="#" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="favoriteLanguage">Favorite language</label>
                                        <select name="favoriteLanguage" class="form-control">
                                            <option value="" <?= (!isset($langSelected)) ? 'selected' : '' ?>>---</option>
                                            <?php foreach ($languages as $lang) : ?>
                                                <option value="<?= $lang->getId() ?>" <?= ($langSelected == $lang->getId()) ? 'selected' : '' ?>>
                                                    <?= $lang->getName() ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Edit favorite language</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border">
                                <form action="#" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="dateOfBirth">Biography</label>
                                        <textarea class="form-control " name="biography" rows="4" cols="50"><?= $biography ?></textarea>
                                    </div>
                                    <button class="btn btn-primary">Edit biography</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>