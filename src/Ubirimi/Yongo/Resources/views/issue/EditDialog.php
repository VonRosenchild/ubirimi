<?php
    use Ubirimi\Yongo\Repository\Issue\SystemOperation;
?>
<body>
    <div style="padding-left: 0px; margin-right: 0px; max-height: 600px;">
        <div>
            <div id="errosMandatoryFieldsNotPresentOnScreen"></div>
            <?php require_once __DIR__ . '/ViewEditDialog.php' ?>
        </div>
    </div>
    <input type="hidden" value="<?php echo $issueData['issue_project_id'] ?>" id="project_id" />
    <input type="hidden" value="<?php echo SystemOperation::OPERATION_EDIT ?>" id="operation_id" />
</body>