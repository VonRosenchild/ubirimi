<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../views/administration/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/helpdesk/administration/organizations">Organizations</a> > Update Organization') ?>
    <div class="pageContent">
        <form name="add_status" action="/helpdesk/administration/organizations/edit/<?php echo $organizationId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $organization['name']; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($duplicateOrganization): ?>
                            <div class="error">An organization with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php echo $organization['description']; ?></textarea>
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
                            <button type="submit" name="edit_organization" class="btn ubirimi-btn"><i class="icon-plus"></i> Update Organization</button>
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