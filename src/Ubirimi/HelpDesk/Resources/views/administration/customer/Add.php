<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../Resources/views/administration/_menu.php'; ?>

    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/helpdesk/administration/customers?id=' . $organizationId . '">Customers</a> > Create Customer';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <form id="form_add_customer" name="add_customer" action="/helpdesk/administration/customers/add<?php if ($organizationId) echo '?id=' . $organizationId ?>" method="post">
                <table width="100%">
                    <tr>
                        <td valign="top" width="150">Email address <span class="mandatory">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($email)) echo $email ?>" name="email" />
                            <?php if ($errors['empty_email']): ?>
                                <div class="error">The email address can not be empty.</div>
                            <?php elseif ($errors['email_not_valid']): ?>
                                <div class="error">The email address is not valid.</div>
                            <?php elseif ($errors['email_already_exists']): ?>
                                <div class="error">The email address is already assigned to another customer.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">First name</td>
                        <td>
                            <input class="inputText" type="text" name="first_name" value="<?php if (isset($firstName)) echo $firstName ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Last name</td>
                        <td>
                            <input class="inputText" type="text" name="last_name" value="<?php if (isset($lastName)) echo $lastName ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Organization</td>
                        <td>
                            <select name="organization" class="select2InputMedium">
                                <option value="-1">None</option>
                                <?php if ($organizations): ?>
                                    <?php while ($organization = $organizations->fetch_array(MYSQLI_ASSOC)): ?>
                                        <option value="<?php echo $organization['id'] ?>"><?php echo $organization['name'] ?></option>
                                    <?php endwhile ?>
                                <?php endif ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr size="1" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="vertical-align: middle">
                            <button type="submit" class="btn ubirimi-btn" name="confirm_new_customer">Create Customer</button>
                            <a class="btn ubirimi-btn" href="/helpdesk/administration/customers?id=<?php echo $organizationId ?>">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_footer.php' ?>
</body>