<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a href="/yongo/administration/screens" class="linkNoUnderline">Screens</a> > Create Screen') ?>
    <div class="pageContent">
        <form name="add_screen" action="/yongo/administration/screen/add" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="150">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
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
                    <td colspan="2">Add the following fields to the screen</td>
                </tr>
                <?php while ($field = $fields->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td colspan="2">
                            <input type="checkbox" value="1" name="field_<?php echo $field['id'] ?>" id="field_<?php echo $field['id'] ?>" />
                            <span><label for="field_<?php echo $field['id'] ?>"><?php echo $field['name'] ?></label></span>
                        </td>
                    </tr>
                <?php endwhile ?>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_screen" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Screen</button>
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