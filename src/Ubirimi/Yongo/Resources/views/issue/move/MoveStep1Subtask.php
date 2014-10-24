<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    require_once __DIR__ . '/../../_header.php';
?>
<body style="background-color: #EEEEEE ;">

<?php require_once __DIR__ . '/../../_menu.php'; ?>

<div class="pageContent">

    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    <span>Move Issue</span>
                    <br />
                    <a class="linkNoUnderline" href="/yongo/project/<?php echo $projectId ?>"><?php echo $issueProject['name'] ?></a> /
                    <?php echo $issue['project_code'] . '-' . $issue['nr'] ?>
                </div>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td valign="top" width="240px">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td><b>Select Project and Issue Type</b></td>
                    </tr>
                    <tr>
                        <td>Select New Status</td>
                    </tr>
                    <tr>
                        <td>Update Fields</td>
                    </tr>
                    <tr>
                        <td>Confirmation</td>
                    </tr>
                </table>
            </td>
            <td width="15px"></td>
            <td valign="top">
                <form name="move_issue_step_1_subtask" method="post" action="/yongo/issue/move/subtask-issue-type/<?php echo $issue['id'] ?>">
                    <b>Step 1 of 4: Select Issue Types for Sub-Tasks</b>
                    <br />
                    The table below lists all the sub-tasks that need to be moved to a new project. Please select the appropriate issue type for each of them.
                    <br>
                    <div><b>Select New Issue Types for Sub-Tasks</b></div>
                    <hr size="1" />
                    <table width="100%">
                        <?php for ($i = 0; $i < count($oldSubtaskIssueType); $i++): ?>
                            <?php $subTask = IssueType::getById($oldSubtaskIssueType[$i]); ?>
                            <tr>
                                <td width="25%"><b>Current Issue Type</b></td>
                                <td width="25%"><?php echo $subTask['name'] ?></td>
                                <td width="25%"><b>New Issue Type</b></td>
                                <td width="25%">
                                    <select name="new_subtask_issue_type_<?php echo $oldSubtaskIssueType[$i] ?>" class="inputTextCombo">
                                        <?php while ($newSubtaskIssueType && $issueType = $newSubtaskIssueType->fetch_array(MYSQLI_ASSOC)): ?>
                                            <option value="<?php echo $issueType['id'] ?>"><?php echo $issueType['name'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                    <?php if ($errorNoNewSubtaskIssueTypeSelected): ?>
                                        <br />
                                        <span class="mandatory">You must select a new sub-task issue type.</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                            <?php if ($newSubtaskIssueType) $newSubtaskIssueType->data_seek(0) ?>
                        <?php endfor ?>
                    </table>
                    <br />
                    <hr size="1" />
                    <div align="left">
                        <button type="submit" name="move_issue_step_1_subtask" class="btn ubirimi-btn">Next</button>
                        <a class="btn ubirimi-btn" href="<?php echo LinkHelper::getYongoIssueViewLinkJustHref($issue['id']) ?>">Cancel</a>
                    </div>
                </form>

            </td>
        </tr>

    </table>

</div>
<?php require_once __DIR__ . '/../../_footer.php' ?>
</body>