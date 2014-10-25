<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/permission-schemes">Permission Schemes</a> > Copy Permission Scheme') ?>
    <div class="pageContent">
        <form name="form_copy_permission_scheme" action="/yongo/administration/permission-scheme/copy/<?php echo $permissionSchemeId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $permissionScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The permission scheme name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">A permission scheme with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $permissionScheme['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="copy_permission_scheme" class="btn ubirimi-btn">Copy Permission Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/permission-schemes">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>