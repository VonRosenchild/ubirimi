<?php

use Ubirimi\Repository\User\UbirimiUser;

require __DIR__ . '/_header.php';
?>
<body>
    <?php require __DIR__ . '/_menu.php'; ?>

    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnUserChangePassword" href="#" class="btn ubirimi-btn">Change Password</a></td>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <td valign="top" width="40%">
                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">User details</span></td>
                        </tr>
                        <tr>
                            <td width="200px;" valign="top">
                                <div class="textLabel">Picture</div>
                            </td>
                            <td style="height: 150px;">

                                <span class="fileinput-button picture_user_avatar">
                                    <span>
                                        <img id="profile-picture"
                                             style="width: 150px; height: 150px; vertical-align: top"
                                             title="<?php echo $user['first_name'] . ' ' . $user['last_name'] ?>"
                                             src="<?php echo $this->getRepository(UbirimiUser::class)->getUserAvatarPicture($user, 'big') ?>" />
                                        <img id="loading" style="display: none" src="/img/loader.gif" />
                                    </span>
                                    <?php if ($loggedInUserId == $userId): ?>
                                        <input id="fileupload" type="file" name="files[]" multiple="multiple" style="font-size: 150px; width: 1040px;" />
                                    <?php endif ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Full Name:</div>
                            </td>
                            <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textLabel">Email address:</div>
                            </td>
                            <td><?php echo $user['email'] ?></td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Preferences</span></td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Issues per page:</div>
                            </td>
                            <td><?php echo $user['issues_per_page'] ?></td>
                        </tr>
                        <tr>
                            <td width="200px;">
                                <div class="textLabel">Notify own changes:</div>
                            </td>
                            <td>
                                <?php if ($user['notify_own_changes_flag']) echo 'YES'; else echo 'NO'; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
        <div class="ubirimiModalDialog" id="modalChangePassword"></div>
    </div>
</body>