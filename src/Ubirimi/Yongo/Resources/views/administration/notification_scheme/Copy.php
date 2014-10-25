<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/notification-schemes">Notification Schemes</a> > Copy Notification Scheme') ?>
    <div class="pageContent">
        <form name="form_copy_notification_scheme" action="/yongo/administration/notification-scheme/copy/<?php echo $notificationSchemeId ?>" method="post">


            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $notificationScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The notification scheme name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">A notification scheme with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $notificationScheme['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="copy_notification_scheme" class="btn ubirimi-btn">Copy Notification Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/notification-schemes">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>