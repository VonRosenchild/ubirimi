<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Components</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <?php
            $menuProjectCategory = 'components';
            require_once __DIR__ . '/_summaryMenu.php';
            require_once __DIR__ . '/_projectButtons.php';
        ?>

        <?php if ($components): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Leader</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($component = $components->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><a href="/yongo/project/component/<?php echo $component['id'] ?>"><?php echo $component['name']; ?></a></td>
                    <td><?php echo $component['description']; ?></td>
                    <td>
                        <?php if ($component['user_id']): ?>
                            <?php echo LinkHelper::getUserProfileLink($component['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $component['first_name'], $component['last_name']); ?>
                        <?php else: ?>
                            <span>No one</span>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteProjectComponent"></div>
        <?php else: ?>
            <div class="messageGreen">There are no components defined for this project.</div>
        <?php endif ?>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id" />
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>