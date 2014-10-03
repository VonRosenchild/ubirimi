<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <form name="add_custom_field" action="/yongo/administration/custom-field/add" method="post">
            <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/custom-fields">Custom Fields</a> > Create Custom Field - Choose Field Type (Step 1 of 2)') ?>
            <table width="100%">
                <?php while ($type = $types->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td width="20" style="vertical-align: middle">
                            <input class="radio"
                                   type="radio"
                                   name="type"
                                   id="label_<?php echo $type['code'] ?>"
                                   value="<?php echo $type['code'] ?>">
                        </td>
                        <td>
                            <label for="label_<?php echo $type['code'] ?>">
                                <?php echo $type['name'] ?>
                                <div class="smallDescription"><?php echo $type['description'] ?></div>
                            </label>
                        </td>
                    </tr>
                <?php endwhile ?>
                <?php if ($emptyType): ?>
                    <tr>
                        <td colspan="2">
                            <div class="error">Please select a custom field type</div>
                        </td>
                    </tr>
                <?php endif ?>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="new_custom_field" class="btn ubirimi-btn">Next Step</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/custom-fields">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>