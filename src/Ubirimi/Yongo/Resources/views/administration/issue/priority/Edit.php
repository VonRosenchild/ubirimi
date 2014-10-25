<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue/priorities">Issue Priorities</a> > Edit Priority') ?>
    <div class="pageContent">

        <form name="add_priority" action="/yongo/administration/issue/priority/edit/<?php echo $Id ?>" method="post">

            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input type="text" class="inputText" value="<?php echo $issuePriority['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($priorityExists): ?>
                            <div class="error">A priority with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php echo $issuePriority['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Color</td>
                    <td>
                        <input class="inputText color {hash:true}" style="width: 100px" name="color" value="<?php echo $issuePriority['color'] ?>" />
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
                            <button type="submit" name="edit_priority" class="btn ubirimi-btn"><i class="icon-edit"></i> Update priority</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue/priorities">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>