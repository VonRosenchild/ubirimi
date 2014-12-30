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
                            <td>
                                <a href="/yongo/issue/move/project/<?php echo $issue['id'] ?>">Select Project and Issue Type</a></b>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp; Project: <b><?php echo $newProject['name'] ?></b>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp; Type: <b><?php echo $newTypeName ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a href="/yongo/issue/move/status/<?php echo $issue['id'] ?>">Select New Status</a>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp;Status: <b><?php echo $newStatusName ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="/yongo/issue/move/fields/<?php echo $issue['id'] ?>">Update Fields</a></td>
                        </tr>
                        <tr>
                            <td><b>Confirmation</b></td>
                        </tr>
                    </table>
                </td>
                <td width="15px"></td>
                <td valign="top">
                    <form name="move_issue_step_4" method="post" action="/yongo/issue/move/confirmation/<?php echo $issue['id'] ?>">
                        <b>Step 4 of 4: Confirm the move with all of the details you have just configured.</b>
                        <br>
                        Note: Sub-Tasks associated with this issue will lose data stored in fields not applicable in the target.
                        <table width="100%">
                            <tr>
                                <td width="30%"></td>
                                <td width="35%"><b>Original Value (before move)</b></td>
                                <td width="35%"><b>New Value (after move)</b></td>
                            </tr>
                            <tr>
                                <td>Project</td>
                                <td><?php echo $issueProject['name'] ?></td>
                                <td><?php echo $newProject['name'] ?></td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td><?php echo $issue['type_name'] ?></td>
                                <td><?php echo $newTypeName ?></td>
                            </tr>
                            <?php if (($issueComponents && $issueComponents->num_rows) || ($newIssueComponents && $newIssueComponents->num_rows)): ?>
                                <tr>
                                    <td>Components</td>
                                    <td>
                                        <?php while ($issueComponents && $issueComponent = $issueComponents->fetch_array(MYSQLI_ASSOC)): ?>
                                            <?php echo $issueComponent['name'] ?>
                                        <?php endwhile ?>
                                    </td>
                                    <td>
                                        <?php if ($newIssueComponents): ?>
                                            <?php while ($issueComponent = $newIssueComponents->fetch_array(MYSQLI_ASSOC)): ?>
                                                <?php echo $issueComponent['name'] ?>
                                            <?php endwhile ?>
                                        <?php else: ?>
                                            <div>None</div>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php if (($issueFixVersions && $issueFixVersions->num_rows) || ($issueFixVersions && $newIssueFixVersions->num_rows)): ?>
                                <tr>
                                    <td>Fix Versions</td>
                                    <td>
                                        <?php while ($issueFixVersions && $issueVersion = $issueFixVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                            <?php echo $issueVersion['name'] ?>
                                        <?php endwhile ?>
                                    </td>
                                    <td>
                                        <?php if ($newIssueFixVersions): ?>
                                            <?php while ($issueVersion = $newIssueFixVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                                <?php echo $issueVersion['name'] ?>
                                            <?php endwhile ?>
                                        <?php else: ?>
                                            <div>None</div>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php if (($issueAffectedVersions && $issueAffectedVersions->num_rows) || ($issueAffectedVersions && $newIssueAffectsVersions->num_rows)): ?>
                                <tr>
                                    <td>Affects Versions</td>
                                    <td>
                                        <?php while ($issueAffectedVersions && $issueVersion = $issueAffectedVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                            <?php echo $issueVersion['name'] ?>
                                        <?php endwhile ?>
                                    </td>
                                    <td>
                                        <?php if ($newIssueFixVersions): ?>
                                            <?php while ($issueVersion = $newIssueAffectsVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                                <?php echo $issueVersion['name'] ?>
                                            <?php endwhile ?>
                                        <?php else: ?>
                                            <div>None</div>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                            <?php if ($session->get('move_issue/new_assignee') && $issue['assignee'] != $session->get('move_issue/new_assignee')): ?>
                                <tr>
                                    <td>Assignee </td>
                                    <td>
                                        <?php echo $issue['ua_first_name'] . ' ' . $issue['ua_last_name'] ?>
                                    </td>
                                    <td>
                                        <?php echo $newUserAssignee['first_name'] . ' ' . $newUserAssignee['last_name'] ?>
                                    </td>
                                </tr>
                            <?php endif ?>
                        </table>
                        <hr size="1" />
                        <div align="left">
                            <button type="submit" name="move_issue_step_4" class="btn ubirimi-btn">Move</button>
                            <a class="btn ubirimi-btn" href="<?php echo LinkHelper::getYongoIssueViewLinkJustHref($issue['id']) ?>">Cancel</a>
                        </div>

                    </form>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>