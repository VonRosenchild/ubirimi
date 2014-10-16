<?php
    use Ubirimi\Yongo\Repository\Screen\Screen;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/field-configurations">Field Configurations</a> >
                        <a class="linkNoUnderline" href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfiguration['id'] ?>"><?php echo $fieldConfiguration['name'] ?></a> >
                        Associate <?php echo $field['name'] ?> to screens
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="form_edit_field_configuration_screen" action="/yongo/administration/field-configuration/edit-screen/<?php echo $fieldConfigurationId ?>/<?php echo $fieldId ?>" method="post">

            <table class="table table-hover table-condensed">
                <tr>
                    <td width="400">Screen</td>
                    <th>Select</th>
                </tr>

                <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $screen['name'] ?></td>
                        <td>
                            <?php $fieldInScreen = Screen::checkFieldInScreen($clientId, $screen['id'], $fieldId); ?>
                            <input type="checkbox" <?php if ($fieldInScreen) echo 'checked="checked"' ?> name="field_screen_<?php echo $fieldId ?>_<?php echo $screen['id'] ?>" />
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
            <table width="100%">
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_field_configuration_screen" class="btn ubirimi-btn"><i class="icon-edit"></i> Update</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfigurationId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>