<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php if (Util::userHasDocumentatorAdministrativePermission()): ?>
        <?php
            $breadCrumb = '<a href="/yongo/administration/custom-fields" class="linkNoUnderline">Custom Fields</a> > ' . $field['name'] . ' > Update Custom Value';
            Util::renderBreadCrumb($breadCrumb);
        ?>
    <?php endif ?>

    <div class="pageContent">
        <?php if (Util::userHasDocumentatorAdministrativePermission()): ?>
            <form name="form_update_custom_field_value" action="/yongo/administration/custom-field/value/edit/<?php echo $valueId ?>" method="post">

                <table width="100%">
                    <tr>
                        <td width="150" valign="top">Value <span class="error">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($fieldValue['value'])) echo $fieldValue['value'] ?>" name="value" />
                            <?php if ($emptyValue): ?>
                                <br />
                                <div class="error">The value can not be empty.</div>
                            <?php elseif ($duplicateValue): ?>
                                <br />
                                <div class="error">The value is not available.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" name="edit_custom_field_value" class="btn ubirimi-btn"><i class="icon-plus"></i> Update Custom Value</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/custom-fields/define/<?php echo $customFieldId ?>">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>