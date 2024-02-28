<?php

?>
<section class="probootstrap-section probootstrap-section-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row probootstrap-gutter0">
                    <div class="col-md-12" id="probootstrap-sidebar">
                        <h2>Edit your profile</h2>
                        <?php include __DIR__ . '/parts/show-error.php' ?>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-username" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" value="<?= $username ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-5 border">
                                <form action="/validate-email" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" value="<?= $email ?>" />
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-password" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" />
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm password</label>
                                        <input type="password" class="form-control" name="confirmPassword" />
                                    </div>
                                    <button class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                            <div class="col-md-5 border">
                                <form action="/validate-profile-picture" method="post" class="probootstrap-form" enctype="multipart/form-data" style="display: inline;" novalidate>
                                    <div class="form-group">
                                        <label for="profilePicture">Profile Picture</label>
                                        <input type="file" class="form-control-file" name="profilePicture" />
                                    </div>
                                    <div>
                                        <img src="<?= $pfpPreview->getProfilePicturesPath() ?>" alt="" width="100px">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                                <form class="probootstrap-form" action="/delete-profile-picture" method="post" style="display: inline-block;" novalidate>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 border">
                                <form action="/validate-birthday" method="post" class="probootstrap-form" style="display: inline;" novalidate>
                                    <div class="form-group">
                                        <label for="dateOfBirth">Date of Birth</label>
                                        <input type="date" class="form-control " name="dateOfBirth" value="<?= $dateOfBirth ?>" />
                                    </div>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                                <form class="probootstrap-form" action="/delete-birthday" method="post" style="display: inline-block;" novalidate>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                            <div class="col-md-5 border">
                                <form action="/validate-favorite-language" method="post" class="probootstrap-form" style="display: inline;" novalidate>
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
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                                <form class="probootstrap-form" action="/delete-favorite-language" method="post" style="display: inline-block;" novalidate>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row">
                            <div class="col-md-10 border">
                                <form action="/validate-biography" method="post" class="probootstrap-form" novalidate>
                                    <div class="form-group">
                                        <label for="biography">Biography</label>
                                        <textarea class="form-control " name="biography" rows="4" cols="50"><?= $biography ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>