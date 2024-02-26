<?php
echo '<h1>$user</h1>';
var_dump($user);
echo '<h1>$profilePictureObject</h1>';
var_dump($profilePictureObject);
var_dump($profilePictureObject->getProfilePicturesPath());

use macchiato_academy\app\utils\Utils;

?>

<section class="proboostrap-section">
    <div class="container">
        <div class="row">
            <div class="col-md-3 user-data">
                <div class="row">
                    <div class="col-md-12">
                        <img src="<?= $profilePictureObject->getProfilePicturesPath(); ?>" class="pfp" />

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="fw-semi username"><?= $user->getUsername(); ?></h2>
                        <p><?= explode("_", $user->getRole())[1]; ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="attribute">Email:</h2>
                            <p><?= $user->getEmail(); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="attribute">Birthday:</h2>
                            <p>
                                <?= Utils::formatDate($user->getDateOfBirth()) ?? "--" ?>
                            </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="attribute">Member since:</h2>
                            <p><?= Utils::formatDate($user->getDateOfJoin()) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="attribute">Favorite Language:</h2>
                            <p>
                                <?= $favoriteLanguage ?? "--" ?>
                            </p>
                    </div>
                </div>

            </div>
            <div class="col-md-8 user-info">
            <div class="row">
                    <div class="col-md-12">
                        <h3 class="attribute">Biography:</h2>
                            <p><?= $user->getBiography() ?? "--" ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>