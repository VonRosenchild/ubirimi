<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <form name="form_edit_field_configuration_screen" action="/yongo/administration/field-configuration/edit-metadata/<?php echo $fieldConfigurationId ?>/<?php echo $fieldId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/yongo/administration/field-configurations">Field Configurations</a> >
                            <a class="linkNoUnderline" href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfiguration['id'] ?>"><?php echo $fieldConfiguration['name'] ?></a> >
                            Field: <?php echo $field['name'] ?>
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Description</td>
                    <td><textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_field_configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Update</button>
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