<?php
    use Ubirimi\Yongo\Repository\Screen\Screen;

    require_once __DIR__ . '/../../_header.php';
?>

<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <form name="form_edit_field_configuration_screen" action="/yongo/administration/custom-field/edit-field-screen/<?php echo $fieldId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a href="/yongo/administration/custom-fields" class="linkNoUnderline">Custom Fields</a> > Place Field on Screens > <?php echo $field['name'] ?>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th width="400">Screen</th>
                    <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $screen['name'] ?></td>
                        <td>
                            <?php $fieldInScreen = Screen::checkFieldInScreen($clientId, $screen['id'], $fieldId); ?>
                            <input type="checkbox" <?php if ($fieldInScreen) echo 'checked="checked"' ?> name="field_screen_<?php echo $fieldId ?>_<?php echo $screen['id'] ?>" />
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <table width="100%" style="border-spacing: 0" >
                <tr>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_field_custom_screen" class="btn ubirimi-btn">Place</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/custom-fields">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>