<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <form name="edit_notebook" action="/quick-notes/notebook/edit/<?php echo $notebookId ?>" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/quick-notes/my-notebooks">Notebooks</a> > Edit') ?>
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $notebook['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($notebookExists): ?>
                            <div class="error">A notebook with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $notebook['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_notebook" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Notebook</button>
                            <a class="btn ubirimi-btn" href="/quick-notes/my-notebooks">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>