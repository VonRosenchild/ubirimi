<?php
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\SystemOperation;

?>
<div class="parentDialog" style="padding-left: 0; margin-right: 0; min-height: 50px; overflow: auto;">
    <div id="errorsMandatoryFieldsNotPresentOnScreen"></div>
    <div id="messageIssueCreatedDialog" class="messageGreen" style="padding: 8px; display: none"></div>
    <table border="0" cellpadding="2" cellspacing="0" id="tableFieldList" class="modal-table">
        <tr>
            <td width="170">Project</td>
            <td>
                <select id="field_type_project" name="project" class="select2Input">
                    <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                        <option <?php if ($selectedProjectId == $project['id']) echo 'selected="selected"' ?> value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
                    <?php endwhile ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Issue Type</td>
            <td>
                <select id="field_type_<?php echo Field::FIELD_ISSUE_TYPE_CODE ?>" name="type" class="select2Input">
                    <?php while ($type = $issueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                        <option <?php if ($typeId && $typeId == $type['id']) echo 'selected="selected"' ?> value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                    <?php endwhile ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><hr size="1" /></td>
        </tr>

        <?php require_once __DIR__ . '/_dialogCreate.php'; ?>

    </table>
</div>

<input type="hidden" value="<?php echo $selectedProjectId ?>" id="project_id" />
<input type="hidden" value="<?php echo SystemOperation::OPERATION_CREATE ?>" id="operation_id" />
