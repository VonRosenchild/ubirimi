<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Project\YongoProject;

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
                    <div class="headerPageText"><?php echo $project['name'] ?> > Roles</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <?php
            $menuProjectCategory = 'roles';
            require_once __DIR__ . '/_summaryMenu.php';
            require_once __DIR__ . '/_projectButtons.php';
        ?>

        <?php if ($allRoles): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="200px">Project Roles</th>
                        <th>Users</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($role = $allRoles->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td><?php echo $role['name']; ?></td>
                            <td>
                                <?php $usersInRole = UbirimiContainer::get()['repository']->get(YongoProject::class)->getAllUsersInRole($projectId, $role['id']); ?>
                                <?php if ($usersInRole): ?>
                                    <?php while ($user = $usersInRole->fetch_array(MYSQLI_ASSOC)): ?>
                                        <?php echo LinkHelper::getUserProfileLink($user['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $user['first_name'], $user['last_name']) ?>
                                    <?php endwhile ?>
                                <?php else: ?>
                                    <div>None</div>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteProjectComponent"></div>
        <?php else: ?>
            <div class="messageGreen">There are no roles defined for this project.</div>
        <?php endif ?>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id" />
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>