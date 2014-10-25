<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
    $section = 'activity_stream';
?>

<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php Util::renderBreadCrumb('Home > Activity Stream') ?>

    <div class="pageContent">
        <?php require_once __DIR__ . '/_home_subtabs.php' ?>

        <?php require_once __DIR__ . '/../../Resources/views/project/_activityStream.php'; ?>
        <?php if ($historyList): ?>
            <input type="button" id="get_next_activity" class="btn ubirimi-btn" value="Show More..." />
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>