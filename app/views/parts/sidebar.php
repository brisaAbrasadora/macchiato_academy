<?php
use macchiato_academy\app\utils\Utils;
?>
<div class="col-md-4" id="probootstrap-sidebar">
    <div class="probootstrap-sidebar-inner probootstrap-overlap probootstrap-animate">
        <h3>Sections</h3>
        <ul class="probootstrap-side-menu">
            <?php
            foreach ($sidebar as $title => $url) :
                if (Utils::isActive($url)) { ?>
                    <li class="active">
                        <a>
                            <?= $title ?>
                        </a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?= $url ?>">
                            <?= $title ?>
                        </a>
                    </li>
            <?php }
            endforeach; ?>
        </ul>
    </div>
</div>