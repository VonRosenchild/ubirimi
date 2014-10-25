<?php
use Ubirimi\LinkHelper;

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
                            <td>
                                <a href="/yongo/issue/move/project/<?php echo $issue['id'] ?>">Select Project and Issue Type</a></b>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp; Project: <b><?php echo $newProjectName ?></b>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp; Type: <b><?php echo $newTypeName ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Select New Status</b></td>
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
                    <form name="move_issue_step_2" method="post" action="/yongo/issue/move/status/<?php echo $issue['id'] ?>">
                        <b>Step 2 of 4: Select the status of the issue</b>
                        <br>
                        <div>Current Issue (Workflow: <?php echo $currentWorkflow['name'] ?> -> <?php echo $newWorkflow['name'] ?>)</div>
                        <hr size="1" />
                        <table width="100%">
                            <tr>
                                <td width="25%">Current Status</td>
                                <td width="25%"><?php echo $issue['status_name'] ?></td>
                                <td width="25%"><b>New Status</b></td>
                                <td width="25%">
                                    <select name="move_to_status" class="inputTextCombo">
                                        <?php while ($newStatuses && $status = $newStatuses->fetch_array(MYSQLI_ASSOC)): ?>
                                            <option value="<?php echo $status['linked_issue_status_id'] ?>"><?php echo $status['name'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <hr size="1" />
                        <div align="left">
                            <button type="submit" name="move_issue_step_2" class="btn ubirimi-btn">Next</button>
                            <a class="btn ubirimi-btn" href="<?php echo LinkHelper::getYongoIssueViewLinkJustHref($issue['id']) ?>">Cancel</a>
                        </div>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>