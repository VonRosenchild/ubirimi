<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Users') ?>

    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/general-settings/users/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create User</a></td>
                    <td><a id="btnEditUser" href="#" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteUser" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
        <?php endif ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <?php if (Util::userHasClientAdministrationPermission()): ?>
                        <th></th>
                    <?php endif ?>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email Address</th>
                    <th>Helpdesk Customer</th>
                    <th>Client Administrator</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr <?php if (Util::userHasClientAdministrationPermission()): ?> id="table_row_<?php echo $user['id'] ?>" <?php endif ?>>
                        <?php if (Util::userHasClientAdministrationPermission()): ?>
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $user['id'] ?>" />
                            </td>
                        <?php endif ?>
                        <td>
                            <?php echo LinkHelper::getUserProfileLink($user['id'], SystemProduct::SYS_PRODUCT_YONGO, $user['first_name'], $user['last_name']) ?>
                        </td>
                        <td><?php echo $user['username'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                        <td>
                            <?php if ($user['customer_service_desk_flag']): ?>
                                <span>Yes</span>
                            <?php else: ?>
                                <span>No</span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($user['client_administrator_flag']): ?>
                                <span>Yes</span>
                            <?php else: ?>
                                <span>No</span>
                            <?php endif ?>
                        </td>
                        <td>
                            <a href="/general-settings/users/edit/<?php echo $user['id'] ?>">Edit</a>
                            &middot;
                            <a href="#" class="deleteFromGeneralList" data="<?php echo $user['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteUser"></div>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>