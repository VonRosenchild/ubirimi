<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Settings;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

    Util::checkUserIsLoggedInAndRedirect();

    $functionId = $_POST['function_id'];

    $function = WorkflowFunction::getById($functionId);
    $issueResolutions = Settings::getAllIssueSettings('resolution', $clientId);
?>
<?php
    if ($functionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE): ?>
    <div>Description: <?php echo $function['description'] ?></div>
    <div>
        <span>Issue field</span>
        <select name="issue_field" id="post_function_issue_field" class="inputTextCombo">
            <option value="resolution">Resolution</option>
        </select>
    </div>
    <div>
        <span>Field value</span>
        <select name="field_value" id="post_function_field_value" class="inputTextCombo">
            <option value="-1">Clear value</option>
            <?php while ($resolution = $issueResolutions->fetch_array(MYSQLI_ASSOC)): ?>
                <option value="<?php echo $resolution['id'] ?>"><?php echo $resolution['name'] ?></option>
            <?php endwhile ?>
        </select>
    </div>
<?php endif ?>