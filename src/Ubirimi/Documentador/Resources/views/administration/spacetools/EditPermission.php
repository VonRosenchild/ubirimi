<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Space\Space;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/documentador/administration/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/page/view/<?php echo $space['home_entity_id'] ?>"><?php echo $space['name'] ?></a> > Permissions > Update</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_space_permissions" action="/documentador/administration/space-tools/edit-permissions/<?php echo $spaceId ?>" method="post">

            <div class="headerPageText">Groups</div>
            <div>These are the permissions currently assigned to groups for this space.</div>
            <?php if ($groups): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="10%" align="center">
                                <div><b>All</b></div>
                                <div>View</div>
                            </th>
                            <th width="10%" align="center">
                                <div><b>Space</b></div>
                                <div>Admin</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php $groupPermission = UbirimiContainer::get()['repository']->get(Space::class)->getGroupPermission($spaceId, $group['id']) ?>
                            <tr>
                                <td><?php echo $group['name'] ?></td>
                                <td align="center">
                                    <?php if ($groupPermission && $groupPermission['all_view_flag']): ?>
                                        <input type="checkbox" value="1" checked="checked" name="space_group_all_view_flag_<?php echo $group['id'] ?>" />
                                    <?php else: ?>
                                        <input type="checkbox" value="1" name="space_group_all_view_flag_<?php echo $group['id'] ?>" />
                                    <?php endif ?>
                                </td>
                                <td align="center">
                                    <?php if ($groupPermission && $groupPermission['space_admin_flag']): ?>
                                        <input type="checkbox" value="1" checked="checked" name="space_group_space_admin_flag_<?php echo $group['id'] ?>" />
                                    <?php else: ?>
                                        <input type="checkbox" value="1" name="space_group_space_admin_flag_<?php echo $group['id'] ?>" />
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div>There are currently no groups defined in Documentador.</div>
            <?php endif ?>

            <br />
            <div class="headerPageText">Individual Users</div>
            <div>These are the permissions currently assigned to individual users for this space.</div>

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <td width="10%" align="center">
                            <div><b>All</b></div>
                            <div>View</div>
                        </td>
                        <td width="10%" align="center">
                            <div><b>Space</b></div>
                            <div>Admin</div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                        <?php $userPermission = UbirimiContainer::get()['repository']->get(Space::class)->getUserPermission($spaceId, $user['id']) ?>
                        <tr>
                            <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                            <td align="center">
                                <?php if ($userPermission && $userPermission['all_view_flag']): ?>
                                    <input type="checkbox" value="1" checked="checked" name="space_user_all_view_flag_<?php echo $user['id'] ?>" />
                                <?php else: ?>
                                    <input type="checkbox" value="1" name="space_user_all_view_flag_<?php echo $user['id'] ?>" />
                                <?php endif ?>
                            <td align="center">
                                <?php if ($userPermission && $userPermission['space_admin_flag']): ?>
                                    <input type="checkbox" value="1" checked="checked" name="space_user_space_admin_flag_<?php echo $user['id'] ?>" />
                                <?php else: ?>
                                    <input type="checkbox" value="1" name="space_user_space_admin_flag_<?php echo $user['id'] ?>" />
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>

            <br />
            <div class="headerPageText">Anonymous Access</div>
            <div>When a user is using Documentador while not logged in, they are using it anonymously.</div>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th width="10%">
                            <b>All</b>
                            <div>View</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Anonymous</b></td>
                        <td align="center">
                            <input type="checkbox" value="1" name="anonymous_all_view_flag" <?php if ($anonymousAccessSettings['all_view_flag']) echo 'checked="checked"'; ?> />
                        </td>
                    </tr>
                </tbody>
            </table>
            <br />
            <table width="100%" id="table_list" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left" colspan="2">
                        <button type="submit" name="update_configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Update</button>
                        <a class="btn ubirimi-btn" href="/documentador/administration/space-tools/permissions/<?php echo $spaceId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>