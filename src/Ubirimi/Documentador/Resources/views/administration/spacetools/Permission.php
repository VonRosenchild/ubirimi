<?php

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/documentador/administration/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>"><?php echo $space['name'] ?></a> > Space Tools > Permissions</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>">Overview</a></li>
            <li class="active"><a href="/documentador/administration/space-tools/permissions/<?php echo $spaceId ?>">Permissions</a></li>
            <li><a href="/documentador/administration/space-tools/content/trash/<?php echo $spaceId ?>">Content Tools</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/documentador/administration/space-tools/edit-permissions/<?php echo $spaceId ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Permissions</a></td>
            </tr>
        </table>

        <div class="headerPageText">Groups</div>
        <div>These are the permissions currently assigned to groups for this space.</div>
        <?php if ($groupsWithPermissionForSpace): ?>
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
                    <?php while ($group = $groupsWithPermissionForSpace->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td><?php echo $group['name'] ?></td>
                            <td align="center"><?php if ($group['all_view_flag']) echo 'YES'; else echo 'NO'; ?></td>
                            <td align="center"><?php if ($group['space_admin_flag']) echo 'YES'; else echo 'NO'; ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div>There are currently no groups with access to this space.</div>
        <?php endif ?>

        <br />
        <div class="headerPageText">Individual Users</div>
        <div>These are the permissions currently assigned to individual users for this space.</div>
        <?php if ($usersWithPermissionForSpace): ?>
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
                <?php while ($user = $usersWithPermissionForSpace->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                        <td align="center"><?php if ($user['all_view_flag']) echo 'YES'; else echo 'NO'; ?></td>
                        <td align="center"><?php if ($user['space_admin_flag']) echo 'YES'; else echo 'NO'; ?></td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div>There are currently no users with access to this space.</div>
        <?php endif ?>

        <br />
        <div class="headerPageText">Anonymous Access</div>
        <div>When a user is using Documentador while not logged in, they are using it anonymously.</div>
        <?php if (!$documentatorSettings['anonymous_use_flag']): ?>
            <div class="infoBox">
                <div>WARNING</div>
                <div>Anonymous users will not be able to view this space, because they have not been granted the global ‘Use Documentador’ permission. You can grant anonymous access to Documentador from global <a href="/documentador/administration/global-permissions">permissions</a>.</div>
            </div>
        <?php endif ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th width="10%" align="center">
                        <div><b>All</b></div>
                        <div>View</div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Anonymous</td>
                    <td align="center"><?php if ($anonymousAccessSettings['all_view_flag']) echo 'YES'; else echo 'NO' ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
