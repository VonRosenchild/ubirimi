<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <form name="edit_screen_metadata" action="/yongo/administration/field-configuration/scheme-data/edit/<?php echo $fieldConfigurationSchemeDataId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a> > <?php echo $fieldConfigurationScheme['name'] ?> > Configure
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Issue Type</td>
                    <td>
                        <?php echo ucfirst($fieldConfigurationSchemeData['issue_type_name']) ?>
                    </td>
                </tr>
                <tr>
                    <td>Field Configuration</td>
                    <td>
                        <select name="field_configuration" class="select2InputSmall">
                            <?php while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $fieldConfiguration['id'] ?>"><?php echo $fieldConfiguration['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_field_configuration_scheme_data" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Type Screen Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/field-configuration/scheme/edit/<?php echo $fieldConfigurationSchemeId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $fieldConfigurationSchemeData['issue_type_id'] ?>" name="issue_type" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>