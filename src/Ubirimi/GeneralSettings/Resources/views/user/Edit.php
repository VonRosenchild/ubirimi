<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasClientAdministrationPermission()): ?>
        <?php Util::renderBreadCrumb('<a href="/general-settings/users" class="linkNoUnderline">Users</a> > Edit User') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <form id="form_edit_user" name="edit_user" action="/general-settings/users/edit/<?php echo $userId ?>?location=<?php echo $location ?>" method="post">

                <table width="100%">
                    <tr>
                        <td id="sectDetails" class="sectionDetail" colspan="2"><span class="sectionDetailTitleNoPicture headerPageTextSmall">Details</span></td>
                    </tr>
                    <tr>
                        <td width="140" valign="top">Email address <span class="mandatory">*</td>
                        <td>
                            <input class="inputText" type="text" value="<?php echo $email ?>" name="email"/>
                            <?php if ($errors['empty_email']): ?>
                                <div class="error">The email address can not be empty.</div>
                            <?php elseif ($errors['email_not_valid']): ?>
                                <div class="error">The email address is not valid.</div>
                            <?php elseif ($errors['email_already_exists']): ?>
                                <div class="error">The email address is already assigned to another user.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">First name <span class="mandatory">*</td>
                        <td>
                            <input class="inputText" type="text" name="first_name" value="<?php echo $firstName ?>"/>
                            <?php if ($errors['empty_first_name']): ?>
                                <div class="error">The first name can not be empty.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Last name <span class="mandatory">*</td>
                        <td>
                            <input class="inputText" type="text" name="last_name" value="<?php echo $lastName ?>"/>
                            <?php if ($errors['empty_last_name']): ?>
                                <div class="error">The last name can not be empty.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Username <span class="mandatory">*</td>
                        <td>
                            <input class="inputText" type="text" name="username" value="<?php echo $username ?>"/>
                            <?php if ($errors['empty_username']): ?>
                                <div class="error">The username can not be empty.</div>
                            <?php elseif ($errors['invalid_username']): ?>
                                <div class="error">The username is not valid.</div>
                            <?php elseif ($errors['duplicate_username']): ?>
                                <div class="error">The username is not unique.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td id="sectDetails" class="sectionDetail" colspan="2"><span class="sectionDetailTitleNoPicture headerPageTextSmall">Permissions</span></td>
                    </tr>
                    <tr>
                        <td valign="top">Client Administrator</td>
                        <td>
                            <input type="checkbox" value="1" name="client_administrator_flag" <?php if ($user['client_administrator_flag']) echo 'checked="checked"' ?> />
                            <?php if ($errors['at_least_one_administrator']): ?>
                                <div class="error">There should be at least one administrator left.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Helpdesk Customer</td>
                        <td>
                            <input type="checkbox" value="1" name="customer_service_desk_flag" <?php if ($user['customer_service_desk_flag']) echo 'checked="checked"' ?> />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="right">
                            <div align="left">
                                <button type="submit" class="btn ubirimi-btn" name="confirm_update_user">Update User</button>
                                <a class="btn ubirimi-btn" href="/general-settings/users">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
                <input type="hidden" value="<?php echo $userId ?>" name="user_id"/>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator(); ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>