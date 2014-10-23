<?php
    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText"><a class="linkNoUnderline" href="/svn-hosting/repositories">Repositories</a> > <a class="linkNoUnderline" href="/svn-hosting/repository/<?php echo $svnRepo['id'] ?>"><?php echo $svnRepo['name'] ?></a> > <?php echo $userData['first_name'] . ' ' . $userData['last_name'] ?> > Settings</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/svn-hosting/repository/<?php echo $svnRepoId ?>">Summary</a></li>
            <li class="active"><a href="/svn-hosting/repository/my-settings/<?php echo $svnRepoId ?>">My Settings</a></li>
            <li><a href="/svn-hosting/repository/users/<?php echo $svnRepoId ?>">Users</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a id="btnChangePasswordSvnUser" href="#" class="btn ubirimi-btn">
                        <?php if (empty($userData['password'])): ?>
                            Set My SVN Password
                        <?php else: ?>
                            Change My SVN Password
                        <?php endif ?>
                    </a>
                </td>
                <td>
                    <a id="btnSetPermissionsSvnUser" href="#" class="btn ubirimi-btn">Set My Permissions</a>
                </td>
            </tr>
        </table>

        <?php if (empty($userData['password'])): ?>
            <div class="messageGreen">You did not set a password for the SVN repository yet. Once you do, you can do the checkout.</div>
        <?php endif ?>

        <div>
            <table>
                <tr>
                    <td>Password set:</td>
                    <td><?php if (empty($userData['password'])) echo 'No'; else echo 'Yes' ?></td>
                </tr>
                <tr>
                    <td>Read Permission:</td>
                    <td><?php if ($userData['has_read']) echo 'Yes'; else echo 'No' ?></td>
                </tr>
                <tr>
                    <td>Write Permission:</td>
                    <td><?php if ($userData['has_write']) echo 'Yes'; else echo 'No' ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div id="deleteSvnUser"></div>
    <div class="ubirimiModalDialog" id="modalSVNPermissionsUser"></div>
    <div class="ubirimiModalDialog" id="modalSVNChangePassword"></div>

    <input type="hidden" id="repo_id" value="<?php echo $svnRepoId ?>" />
    <input type="hidden" id="svn_from_user_perspective" value="1" />
    <input type="hidden" id="user_id" value="<?php echo $loggedInUserId ?>" />
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>