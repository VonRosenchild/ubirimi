<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
    $section = 'activity_stream';
?>

<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <div class="pageContent">
        <?php Util::renderBreadCrumb('Home > Activity Stream') ?>

        <?php require_once __DIR__ . '/_home_subtabs.php' ?>

        <?php require_once __DIR__ . '/../../Resources/views/project/ViewActivityStream.php'; ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>