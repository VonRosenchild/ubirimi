<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb('<a href="/yongo/administration/custom-fields">Custom Fields</a> > ' . $field['name'] . ' > Define Custom Values') ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/custom-field/value/add/<?php echo $field['id'] ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Custom Value</a></td>
                <td><a id="btnEditCustomFieldValue" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteCustomFieldValue" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <?php if ($fieldData): ?>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th>Value</th>
                </tr>
                </thead>

                <?php while ($fieldValue = $fieldData->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $fieldValue['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $fieldValue['id'] ?>" />
                        </td>
                        <td>
                            <?php echo $fieldValue['value']; ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no custom field values defined.</div>
        <?php endif ?>
        <div id="modalDeleteCustomFieldValue"></div>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>