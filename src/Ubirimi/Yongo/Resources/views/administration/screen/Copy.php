<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/screens">Screens</a> > Copy Screen') ?>
    <div class="pageContent">
        <form name="form_copy_screen" action="/yongo/administration/screen/copy/<?php echo $screenId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name</td>
                    <td>
                        <input type="text" value="<?php echo $screen['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyScreenName): ?>
                            <div class="error">The screen name can not be empty.</div>
                        <?php elseif ($screenExists): ?>
                            <div class="error">A screen with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $screen['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_workflow_screen" class="btn ubirimi-btn">Copy Screen</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/screens">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>