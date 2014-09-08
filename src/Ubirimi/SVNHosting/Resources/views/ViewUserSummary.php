<?php

    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;

    require_once __DIR__ . '/_header.php';
?>
<body>
<?php require_once __DIR__ . '/_menu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText"><a class="linkNoUnderline" href="/svn-hosting/repositories">Repositories</a> > <a class="linkNoUnderline" href="/svn-hosting/repository/<?php echo $svnRepo['id'] ?>"><?php echo $svnRepo['name'] ?></a> > Users</div>
            </td>
        </tr>
    </table>

    <ul class="nav nav-tabs" style="padding: 0px;">
        <li><a href="/svn-hosting/repository/<?php echo $svnRepoId ?>">Summary</a></li>
        <li><a href="/svn-hosting/repository/my-settings/<?php echo $svnRepoId ?>">My Settings</a></li>
        <li class="active"><a href="/svn-hosting/repository/users/<?php echo $svnRepoId ?>">Users</a></li>
    </ul>

    <div style="height: 4px"></div>
    <?php if (!empty($svnRepoUserList)): ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
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
                <tr>
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
<input type="hidden" id="repo_id" value="<?php echo $session->get('selected_svn_repo_id') ?>" />
<input type="hidden" id="user_id" value="<?php echo $loggedInUserId ?>" />
<?php require_once __DIR__ . '/_footer.php' ?>
</body>