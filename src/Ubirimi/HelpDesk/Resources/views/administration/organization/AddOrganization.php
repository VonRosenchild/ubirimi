<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../views/administration/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/helpdesk/administration/organizations">Organizations</a> > Create Organization') ?>
    <div class="pageContent">
        <form name="add_status" action="/helpdesk/administration/organizations/add" method="post">

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($statusExists): ?>
                            <div class="error">An organization with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="new_organization" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Organization</button>
                            <a class="btn ubirimi-btn" href="/helpdesk/administration/organizations">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../views/administration/_footer.php' ?>
</body>
</html>