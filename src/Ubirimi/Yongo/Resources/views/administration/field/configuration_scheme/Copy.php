<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a> > Copy Field Configuration Scheme') ?>
    <div class="pageContent">
        <form name="form_copy_field_configuration_scheme" action="/yongo/administration/field-configuration/scheme/copy/<?php echo $fieldConfigurationSchemeId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="<?php echo $fieldConfigurationScheme['name']; ?>" name="name" class="inputText"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The field configuration name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">A field configuration with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $fieldConfigurationScheme['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="copy_field_configuration_scheme" class="btn ubirimi-btn">Copy Field Configuration Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/field-configurations/schemes">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>