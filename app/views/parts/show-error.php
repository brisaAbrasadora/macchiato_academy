<?php if (!empty($message) || !empty($errors)) : ?>
    <div class="alert alert-<?= empty($errors) ? 'info' : 'danger'; ?> alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
        </button>
        <?php if (empty($errors)) : ?>
            <p><?= $message ?></p>
        <?php else : ?>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>