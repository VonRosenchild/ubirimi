<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
$administrationView = false;
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb("Projects") ?>
    <div class="pageContent">
        <?php if ($projects): ?>
            <?php require_once __DIR__ . '/../../../Resources/views/administration/project/_projectInCategory.php' ?>
        <?php else: ?>
            <div class="messageGreen">There are no projects created or projects where you have permission to browse issues.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>