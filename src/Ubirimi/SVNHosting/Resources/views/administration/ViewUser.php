<?php

    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">Administration > <a class="linkNoUnderline" href="/svn-hosting/administration/all-repositories">Repositories</a> > <?php echo $svnRepo['name'] ?> > Users</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/svn-hosting/administration/repository/<?php echo $svnRepoId ?>">Summary</a></li>
            <li class="active"><a href="/svn-hosting/repository/users/<?php echo $svnRepoId ?>">Users</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($isSVNAdministrator): ?>
                    <td>
                        <a id="btnNew" href="/general-settings/users/add?fsvn=<?php echo $request->get('id') ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New User</a>
                    </td>
                <?php endif ?>
                <?php if ($isSVNAdministrator): ?>
                    <td>
                        <a id="btnImportUsersSvn" href="#" class="btn ubirimi-btn">Import users</a>
                    </td>
                <?php endif ?>
                <td>
                    <a id="btnChangePasswordSvnUser" href="#" class="btn ubirimi-btn disabled">Change SVN Password</a>
                </td>
                <td>
                    <a id="btnSetPermissionsSvnUser" href="#" class="btn ubirimi-btn disabled">Set permissions</a>
                </td>
                <td>
                    <a id="btnDeleteSvnUser" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete User</a>
                </td>
            </tr>
        </table>

        <?php if (!empty($svnRepoUserList)): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Has read</th>
                        <th>Has write</th>
                        <th>Password set?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($svnRepoUserList as $repoUser): ?>
                        <tr id="table_row_<?php echo $repoUser['user_id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $repoUser['user_id'] ?>" />
                            </td>
                            <td align="left">
                                <?php echo LinkHelper::getUserProfileLink($repoUser['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $repoUser['first_name'], $repoUser['last_name']) ?>
                            </td>
                            <td><?php echo $repoUser['email'] ?></td>
                            <td><?php echo $repoUser['username'] ?></td>
                            <td>
                                <input type="checkbox" <?php if (1 == $repoUser['has_read']): ?> checked="checked" <?php endif ?> value="1" disabled="disabled" />
                            </td>
                            <td>
                                <input type="checkbox" <?php if (1 == $repoUser['has_write']): ?> checked="checked" <?php endif ?> value="1" disabled="disabled" />
                            </td>
                            <td>
                                <input type="checkbox" <?php if (!empty($repoUser['password'])): ?> checked="checked" <?php endif ?> value="1" disabled="disabled" />
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no users created for this svn repository.</div>
        <?php endif ?>
    </div>

    <div id="deleteSvnUser"></div>
    <div class="ubirimiModalDialog" id="modalSVNPermissionsUser"></div>
    <div class="ubirimiModalDialog" id="modalSVNChangePassword"></div>
    <div class="ubirimiModalDialog" id="modalSVNImportUsers"></div>
    <input type="hidden" id="repo_id" value="<?php echo $svnRepoId ?>" />
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>