<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <form name="add_priority" action="/yongo/administration/issue/resolution/add" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue/resolutions">Issue Resolutions</a> > Create Resolution') ?>
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyName): ?>
                        <div class="error">The name can not be empty.</div>
                        <?php elseif ($resolution_exists): ?>
                        <div class="error">A resolution with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td><textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="new_resolution" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Resolution</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue/resolutions">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>