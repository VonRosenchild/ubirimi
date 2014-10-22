<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <div class="pageContent">
        <?php Util::renderContactSystemAdministrator() ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>