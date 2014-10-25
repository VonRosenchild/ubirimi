<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/project/categories">Project Categories</a> > Create Category') ?>
    <div class="pageContent">
        <form name="add_project_category" action="/yongo/administration/project/category/add" method="post">

            <table width="100%">
                <tr>
                    <td width="110" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the category can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">Duplicate project category name. Please choose another name.</div>
                        <?php endif ?>

                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_project_category" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Category</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/categories">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>