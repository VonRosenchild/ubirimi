<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/general-settings/users">Users</a> > Create User';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <form id="form_add_user" name="add_user" action="/general-settings/users/add" method="post">
                <table width="100%">
                    <tr>
                        <td valign="top" width="150">Username <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($username)) echo $username ?>" name="username" />
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
                        <td valign="top">Password <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="password" value="<?php if (isset($password)) echo $password ?>" name="password" />
                            <?php if ($errors['empty_password']): ?>
                                <div class="error">The password can not be empty.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Confirm Password <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="password" value="<?php if (isset($passwordAgain)) echo $passwordAgain ?>" name="password_again" />
                            <?php if ($errors['password_mismatch']): ?>
                                <div class="error">The passwords do not match.</div>
                            <?php endif ?>
                        </td>
                    </tr>

                    <tr>
                        <td valign="top">Email address <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($email)) echo $email ?>" name="email" />
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
                        <td valign="top">First name <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" name="first_name" value="<?php if (isset($firstName)) echo $firstName ?>" />
                            <?php if ($errors['empty_first_name']): ?>
                                <div class="error">The first name can not be empty.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Last name <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" name="last_name" value="<?php if (isset($lastName)) echo $lastName ?>" />
                            <?php if ($errors['empty_last_name']): ?>
                                <div class="error">The last name can not be empty.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="vertical-align: middle">
                            <button type="submit" class="btn ubirimi-btn" name="confirm_new_user">Create User</button>
                            <?php if (empty($svnRepoId)): ?>
                                <a class="btn ubirimi-btn" href="/general-settings/users">Cancel</a>
                            <?php else: ?>
                                <a class="btn ubirimi-btn" href="/svn-hosting/administration/repository/users/<?php echo $svnRepoId ?>">Cancel</a>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
                <?php if (isset($_GET['fsvn'])): ?>
                    <input type="hidden" name="fsvn" value="<?php if (isset($svnRepoId)) echo $svnRepoId ?>" />
                <?php endif ?>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>