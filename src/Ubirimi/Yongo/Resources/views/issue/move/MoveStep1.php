<?php
use Ubirimi\LinkHelper;

require_once __DIR__ . '/../../_header.php';
?>
<body style="background-color: #EEEEEE ;">

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

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

    <div class="pageContent">
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
                    <form name="move_issue_step_1" method="post" action="/yongo/issue/move/project/<?php echo $issue['id'] ?>">
                        <b>Step 1 of 4: Choose the project and issue type to move to</b>
                        <br>
                        <div>Select Project</div>
                        <hr size="1" />
                        <table width="100%">
                            <tr>
                                <td width="25%">Current Project</td>
                                <td width="25%"><?php echo $issueProject['name'] ?></td>
                                <td width="25%"><b>New Project</b></td>
                                <td width="25%">
                                    <select name="move_to_project" id="move_to_project" class="select2InputSmall">
                                        <?php while ($projectForMoving && $project = $projectForMoving->fetch_array(MYSQLI_ASSOC)): ?>
                                            <?php if ($project['id'] != $issueProject['id']): ?>
                                                <option value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
                                            <?php endif ?>
                                        <?php endwhile ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <div>Select Issue Type</div>
                        <hr size="1" />
                        <table width="100%">
                            <tr>
                                <td width="25%">Current Issue Type</td>
                                <td width="25%"><?php echo $issue['type_name'] ?></td>
                                <td width="25%"><b>New Issue Type</b></td>
                                <td width="25%">
                                    <select name="move_to_issue_type" id="move_to_issue_type" class="select2InputSmall">
                                        <?php while ($moveToIssueTypes && $issueType = $moveToIssueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                                            <option value="<?php echo $issueType['id'] ?>"><?php echo $issueType['name'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <hr size="1" />
                        <div align="left">
                            <button type="submit" name="move_issue_step_1" class="btn ubirimi-btn">Next</button>
                            <a class="btn ubirimi-btn" href="<?php echo LinkHelper::getYongoIssueViewLinkJustHref($issue['id']) ?>">Cancel</a>
                        </div>
                    </form>

                </td>
            </tr>

        </table>

    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>