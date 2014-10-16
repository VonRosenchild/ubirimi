<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/project/categories">Project Categories</a> > <?php echo $category['name'] ?> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_project_category" action="/yongo/administration/project/category/edit/<?php echo $categoryId; ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="130">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $category['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($alreadyExists): ?>
                            <br />
                            <div class="error">A category with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php echo $category['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_release" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Category</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/categories">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>