<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Ubirimi') ?>
    <div class="pageContent">
        <div>
            Contact <a target="_blank" href="https://support.ubirimi.net/">Support</a>
            <br />
            Version: <?php echo UbirimiContainer::get()['app.version'] ?>
        </div>

    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>