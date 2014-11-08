<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <?php if (Util::userHasDocumentadorAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Global Permissions > Update') ?>
    <?php endif ?>

    <div class="pageContent">
        <?php if (Util::userHasDocumentadorAdministrativePermission()): ?>

            <form name="edit_global_settings" action="/documentador/administration/edit-global-permissions" method="post">
                <div class="headerPageText">Anonymous Access</div>
                <div>When a user is using Documentador while not logged in, they are using it anonymously.</div>
                <div>For example: Enabling anonymous 'USE' permission, allows non-logged-in users to browse pages and spaces in Documentador.</div>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>
                                <div>Use Documentador</div>
                            </th>
                            <th>View User Profiles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="60%">Anonymous</td>
                            <td align="center">
                                <input type="checkbox" value="1" name="anonymous_use_flag" <?php if ($documentatorSettings['anonymous_use_flag']) echo 'checked="checked"'; ?> />
                            </td>
                            <td align="center">
                                <input type="checkbox" value="1" name="anonymous_view_user_profile_flag" <?php if ($documentatorSettings['anonymous_view_user_profile_flag']) echo 'checked="checked"'; ?> />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br />

                <div class="headerPageText">Groups</div>
                <div>These are the global permissions currently assigned to groups.</div>
                <?php if ($groups): ?>
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <?php while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                    <th><?php echo $globalsPermission['name'] ?></th>
                                <?php endwhile ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td width="60%"><?php echo $group['name'] ?></td>
                                    <?php $globalsPermissions->data_seek(0); ?>
                                    <?php while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                        <td align="center">
                                            <?php
                                                $data = UbirimiContainer::get()['repository']->get(GlobalPermission::class)->getDataByPermissionIdAndGroupId($clientId, $globalsPermission['id'], $group['id']);
                                                if ($data) {
                                                    echo '<input checked="checked" type="checkbox" value="1" name="group_' . $globalsPermission['id'] . '_' . $group['id'] . '" />';
                                                } else {
                                                    echo '<input type="checkbox" value="1" name="group_' . $globalsPermission['id'] . '_' . $group['id'] . '" />';
                                                }
                                            ?>
                                        </td>
                                    <?php endwhile ?>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="infoBox">There are no groups defined.</div>
                <?php endif ?>
                <br />
                <div class="headerPageText">Individual Users</div>
                <div>These are the global permissions currently assigned to individual users.</div>
                <?php $globalsPermissions->data_seek(0) ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <?php while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                <th><?php echo $globalsPermission['name'] ?></th>
                            <?php endwhile ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr>
                                <td width="60%"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                                <?php $globalsPermissions->data_seek(0); ?>
                                <?php while ($globalsPermission = $globalsPermissions->fetch_array(MYSQLI_ASSOC)): ?>
                                    <td align="center">
                                        <?php
                                            $data = UbirimiContainer::get()['repository']->get(GlobalPermission::class)->getDataByPermissionIdAndUserId($clientId, $globalsPermission['id'], $user['id']);
                                            if ($data) {
                                                echo '<input checked="checked" type="checkbox" value="1" name="user_' . $globalsPermission['id'] . '_' . $user['id'] . '" />';
                                            } else {
                                                echo '<input type="checkbox" value="1" name="user_' . $globalsPermission['id'] . '_' . $user['id'] . '" />';
                                            }
                                        ?>
                                    </td>
                                <?php endwhile ?>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <hr size="1" />
                <table width="100%" id="table_list" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="left">
                            <button type="submit" name="update_configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Permissions</button>
                            <a class="btn ubirimi-btn" href="/documentador/administration/global-permissions">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>