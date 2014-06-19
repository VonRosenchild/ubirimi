<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb(sprintf('<a href="/helpdesk/all">Help Desks</a> > %s > Customer Portal', $project['name'])); ?>

        <?php require_once __DIR__ . '/../../../views/_topMenu.php'; ?>
        <a target="_blank" href="/helpdesk/customer-portal">View the Customer Portal</a>
    </div>

    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_footer.php' ?>
</body>