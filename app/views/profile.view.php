<?php
echo '<h1>$user</h1>';
var_dump($user);
echo '<h1>$profilePictureObject</h1>';
var_dump($profilePictureObject);
var_dump($profilePictureObject->getProfilePicturesPath());

?>

<section class="proboostrap-section">
    <div class="container">
        <img src="<?= $profilePictureObject->getProfilePicturesPath(); ?>" class="pfp" />
    </div>
</section>
<!-- <h1>Id de la imagen en la tabla imagenes: <?= $imageId ?></h1> -->