<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php if (Util::userHasDocumentadorAdministrativePermission()): ?>
        <?php
            $breadCrumb = '<a href="/documentador/administration/groups" class="linkNoUnderline">Groups</a> > Create Group';
            Util::renderBreadCrumb($breadCrumb);
        ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasDocumentadorAdministrativePermission()): ?>
            <form name="add_user_group" action="/documentador/administration/groups/add" method="post">
                <table width="100%">
                    <tr>
                        <td width="150" valign="top">Name <span class="error">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($name)) echo $name ?>" name="name" />
                            <?php if ($emptyName): ?>
                                <br />
                                <div class="error">The name can not be empty.</div>
                            <?php elseif ($duplicateName): ?>
                                <br />
                                <div class="error">The name is not available.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Description</td>
                        <td><textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" name="new_group" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Group</button>
                            <a class="btn ubirimi-btn" href="/documentador/administration/groups">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>