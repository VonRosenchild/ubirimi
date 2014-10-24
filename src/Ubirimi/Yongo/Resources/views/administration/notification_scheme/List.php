<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Notification Schemes') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/notification-scheme/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Notification Scheme</a></td>
                    <td><a id="btnNotifications" href="#" class="btn ubirimi-btn disabled">Notifications</a></td>
                    <td><a id="btnEditNotificationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnCopyNotificationScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteNotificationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
            <?php if ($notificationSchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Projects</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scheme = $notificationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $scheme['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>"/>
                                </td>
                                <td>
                                    <a href="/yongo/administration/notification-scheme/edit/<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></a>
                                    <br />
                                    <?php echo $scheme['description'] ?>
                                </td>
                                <td width="500px">
                                    <?php
                                        $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByNotificationScheme($scheme['id']);
                                        if ($projects) {
                                            echo '<ul>';
                                            while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                                            }
                                            echo '</ul>';
                                            echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="0">';
                                        } else {
                                            echo '<input type="hidden" id="delete_possible_' . $scheme['id'] . '" value="1">';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="/yongo/administration/notification-scheme/edit/<?php echo $scheme['id'] ?>">Notifications</a>
                                    &middot;
                                    <a href="/yongo/administration/notification-scheme/edit-metadata/<?php echo $scheme['id'] ?>">Edit</a>
                                    &middot;
                                    <a href="/yongo/administration/notification-scheme/copy/<?php echo $scheme['id'] ?>">Copy</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no notification schemes defined.</div>
            <?php endif ?>
        </div>
            <div class="ubirimiModalDialog" id="modalDeleteNotificationScheme"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php
    endif ?>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>