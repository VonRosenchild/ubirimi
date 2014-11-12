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
                                &nbsp;&nbsp;&nbsp;&nbsp; Project: <b><?php echo $newProjectName ?></b>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp; Type: <b><?php echo $newTypeName ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php if ($actionTaken): ?>
                                    <a href="/yongo/issue/move/status/<?php echo $issue['id'] ?>">Select New Status</a>
                                <?php else: ?>
                                    Select New Status
                                <?php endif ?>
                                <br />
                                &nbsp;&nbsp;&nbsp;&nbsp;Status: <b><?php echo $newStatusName ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Update Fields</b></td>
                        </tr>
                        <tr>
                            <td>Confirmation</td>
                        </tr>
                    </table>
                </td>
                <td width="15px"></td>
                <td valign="top">
                    <form name="move_issue_step_3" method="post" action="/yongo/issue/move/fields/<?php echo $issue['id'] ?>">
                        <b>Step 3 of 4: Update the fields of the issue to relate to the new project.</b>
                        <br>
                        <?php if ($actionTaken): ?>
                            <table>
                                <?php if ($issueComponents): ?>
                                    <tr>
                                        <td valing="top" style="vertical-align: top">Components:</td>
                                        <td>
                                            <select name="new_component[]" multiple="multiple" class="select2Input" style="width: 300px">
                                                <?php while ($targetProjectComponents && $targetProjectComponent = $targetProjectComponents->fetch_array(MYSQLI_ASSOC)): ?>
                                                    <option value="<?php echo $targetProjectComponent['id'] ?>"><?php echo $targetProjectComponent['name'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($issueFixVersions): ?>
                                    <tr>
                                        <td valing="top" style="vertical-align: top">Fix Versions:</td>
                                        <td>
                                            <select name="new_fix_version[]" multiple="multiple" class="select2Input" style="width: 300px">
                                                <?php while ($targetVersions && $targetVersion = $targetVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                                    <option value="<?php echo $targetVersion['id'] ?>"><?php echo $targetVersion['name'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($issueAffectedVersions): ?>
                                    <?php if ($targetVersions) $targetVersions->data_seek(0) ?>
                                    <tr>
                                        <td valing="top" style="vertical-align: top">Affects Versions:</td>
                                        <td>
                                            <select name="new_affects_version[]" multiple="multiple" class="select2Input" style="width: 300px">
                                                <?php while ($targetVersions && $targetVersion = $targetVersions->fetch_array(MYSQLI_ASSOC)): ?>
                                                    <option value="<?php echo $targetVersion['id'] ?>"><?php echo $targetVersion['name'] ?></option>
                                                <?php endwhile ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endif ?>
                                <?php if ($assigneeChanged): ?>
                                    <tr>
                                        <td valing="top" style="vertical-align: top">Assignee:</td>
                                        <td>
                                            <select name="new_assignee" class="select2Input" style="width: 300px">
                                                <?php foreach ($assignableUsersTargetProjectArray as $user): ?>
                                                    <option value="<?php echo $user['user_id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endif ?>
                            </table>
                        <?php else: ?>
                            <div>All fields will be updated automatically.</div>
                        <?php endif ?>
                        <hr size="1" />
                        <div align="left">
                            <button type="submit" name="move_issue_step_3" class="btn ubirimi-btn">Next</button>
                            <a class="btn ubirimi-btn" href="<?php echo LinkHelper::getYongoIssueViewLinkJustHref($issue['id']) ?>">Cancel</a>
                        </div>

                    </form>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>