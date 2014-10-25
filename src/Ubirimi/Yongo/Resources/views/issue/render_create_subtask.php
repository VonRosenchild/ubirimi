<?php
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;

$sysOperationId = SystemOperation::OPERATION_CREATE;
?>
<div style="padding-left: 0px; margin-right: 0px; max-height: 600px;">
    <div>
        <div id="errorsMandatoryFieldsNotPresentOnScreen"></div>
        <table border="0" cellpadding="2" cellspacing="0" id="tableFieldList" class="modal-table">
            <tr>
                <td>Issue Type</td>
                <td>
                    <select id="field_type_<?php echo Field::FIELD_ISSUE_TYPE_CODE ?>" name="type" class="select2Input">
                        <?php while ($type = $issue_types->fetch_array(MYSQLI_ASSOC)): ?>
                            <option <?php if ($typeId && $typeId == $type['id']) echo 'selected="selected"' ?> value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                        <?php endwhile ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr size="1" />
                </td>
            </tr>
            <tr></tr>
            <?php require_once __DIR__ . '/_dialogCreate.php' ?>
        </table>
    </div>
</div>
<input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
<input type="hidden" value="<?php echo SystemOperation::OPERATION_CREATE ?>" id="operation_id" />