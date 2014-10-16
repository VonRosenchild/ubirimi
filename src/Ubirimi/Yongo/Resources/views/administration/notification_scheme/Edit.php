<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%" class="">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/notification-schemes">Notification Schemes</a> > <?php echo $notificationScheme['name'] ?> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <form name="edit_notification_scheme_metadata" action="/yongo/administration/notification-scheme/edit-metadata/<?php echo $notificationSchemeId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $notificationScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                        <div class="error">The notification scheme name can not be empty.</div>
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
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_notification_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Notification Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/notification-schemes">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>