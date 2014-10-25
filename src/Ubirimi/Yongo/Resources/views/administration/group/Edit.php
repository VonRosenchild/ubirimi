<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/groups" class="linkNoUnderline">Groups</a> > ' . $group['name'] . ' > Edit';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="edit_user_group" action="/yongo/administration/group/edit/<?php echo $Id ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $name ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name can not be empty</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">The name is not available</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td valign="top">
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="update_group" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Group</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/groups">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>