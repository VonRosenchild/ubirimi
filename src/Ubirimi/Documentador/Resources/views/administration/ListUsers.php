<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>
<?php require_once __DIR__ . '/_menu.php'; ?>
<div class="pageContent">
    <?php if (Util::userHasDocumentatorAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Users') ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnAssignUserToGroupDocumentator" href="#" class="btn ubirimi-btn disabled">Groups</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $user['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $user['id'] ?>" />
                        </td>
                        <td>
                            <?php echo LinkHelper::getUserProfileLink($user['id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $user['first_name'], $user['last_name']) ?>
                        </td>
                        <td><?php echo $user['username'] ?></td>
                        <td><?php echo $user['email'] ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteUser"></div>
        <div class="ubirimiModalDialog" id="modalAssignUserToGroup"></div>
        <input type="hidden" value="<?php echo SystemProduct::SYS_PRODUCT_DOCUMENTADOR ?>" id="product_id" />
    <?php else: ?>
        <div class="infoBox">Unauthorized access. Please contact the system administrator.</div>
    <?php endif ?>
</div>
<?php require_once __DIR__ . '/_footer.php' ?>
</body>