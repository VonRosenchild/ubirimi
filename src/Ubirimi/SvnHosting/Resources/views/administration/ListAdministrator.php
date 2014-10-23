<?php

use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php Util::renderBreadCrumb('Administration > SVN Administrators') ?>

    <div class="pageContent">
        <?php if ($isSVNAdministrator): ?>
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li>
                    <a href="/svn-hosting/administration/all-repositories">Repositories</a>
                </li>
                <li class="active">
                    <a href="/svn-hosting/administration/administrators">Administrators</a>
                </li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/svn-hosting/administration/add-administrator" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Administrator</a></td>
                    <td><a id="btnDeleteSvnAdministrator" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>

            <?php if (!empty($svnAdministrators)): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>First & Last Name</th>
                            <th>Username</th>
                            <th>Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($user = $svnAdministrators->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $user['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $user['id'] ?>" />
                            </td>
                            <td align="left">
                                <?php echo LinkHelper::getUserProfileLink($user['id'], SystemProduct::SYS_PRODUCT_YONGO, $user['first_name'], $user['last_name']) ?>
                            </td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                    <?php endwhile ?>
                    </tbody>
                    <div id="deleteSvnRepo"></div>
                </table>
                <div class="ubirimiModalDialog" id="modalDeleteSVNAdministrator"></div>
                <input type="hidden" value="<?php if ($svnAdministrators && $svnAdministrators->num_rows == 1) echo '0'; else echo "1" ?>" id="delete_possible" />
            <?php else: ?>
                <div class="messageGreen">There are no SVN administrators.</div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">You do not have the privileges to access this page.</div>
        <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/_footer.php' ?>
</body>