<?php
    use Ubirimi\Repository\Client;
use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    require_once __DIR__ . '/../_header.php';
?>
<body style="background-color: #EEEEEE ;">

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <div class="pageContent">
        <?php if (!$issueValid): ?>
            <div class="infoBox">This issue does not exist or you do not have the permission to view it.</div>
        <?php else: ?>

            <?php require_once __DIR__ . '/_titleSummary.php' ?>

            <?php require_once __DIR__ . '/_topButtons.php' ?>

            <div class="separationVertical"></div>
            <div class="separationVertical"></div>
            <div class="separationVertical"></div>
            <div class="separationVertical"></div>

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td id="sectDetails" width="70%" class="sectionDetail" colspan="3"><span class="sectionDetailTitle headerPageText">Details</span></td>
                    <td width="20"></td>
                    <td rowspan="4" valign="top">
                        <?php if ($atLeastOneSLA): ?>
                            <?php require_once __DIR__ . '/_sectionDetailsSlas.php' ?>
                        <?php endif ?>
                        <?php require_once __DIR__ . '/_sectionDetailsDates.php' ?>

                        <?php if ($timeTrackingFlag): ?>
                            <?php if ($workLogs): ?>
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td id="sectTimeTracking" class="sectionDetail">
                                            <span class="sectionDetailTitle headerPageText">Time Tracking</span>
                                            <?php if ($hasWorkOnIssuePermission): ?>
                                                <img id="add_issue_log_work" src="/img/plus.png" class="headerPageText menu_img" height="20px" style=" padding-left: 4px; margin-top: 3px; float: right; text-align: right; background-color: #ffffff"/>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php endif ?>
                            <div id="ajax_time_tracking">
                                <?php if ($workLogs): ?>
                                    <?php require __DIR__ . '/_timeTrackingInformation.php' ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php require_once __DIR__ . '/_mainInformation.php' ?>

                        <table width="100%">
                            <?php if (Util::deepInArray('description', $screenData)) : ?>
                                <tr>
                                    <td id="sectDescription" class="sectionDetail" colspan="2"><span class="sectionDetailTitle headerPageText">Description</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="contentDescription"><?php echo str_replace("\n", '<br />', htmlentities($issue['description'])) ?></div>
                                    </td>
                                </tr>
                            <?php endif ?>

                            <?php if ($childrenIssues && $childrenIssues->num_rows): ?>
                                <?php require_once __DIR__ . '/_childrenIssues.php' ?>
                            <?php endif ?>

                            <?php if ($attachments): ?>
                                <?php Util::includePartial(__DIR__ . '/_attachments.php', array('attachments' => $attachments,
                                    'loggedInUserId' => $loggedInUserId,
                                    'hasDeleteAllAttachmentsPermission' => $hasDeleteAllAttachmentsPermission,
                                    'hasDeleteOwnAttachmentsPermission' => $hasDeleteOwnAttachmentsPermission)); ?>
                            <?php endif ?>

                            <?php if ($linkedIssues): ?>
                                <?php require_once __DIR__ . '/../issue/_linked_issues.php' ?>
                            <?php endif ?>

                            <?php if ($issue['environment']): ?>
                                <tr>
                                    <td id="sectEnvironment" class="sectionDetail" colspan="2"><span class="sectionDetailTitle headerPageText">Environment</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="contentEnvironment"><?php echo str_replace("\n", '<br />', htmlentities($issue['environment'])) ?></div>
                                    </td>
                                </tr>
                            <?php endif ?>

                            <tr>
                                <td id="sectActivity" class="sectionDetail" colspan="2">
                                    <span class="sectionDetailTitle headerPageText">Activity</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="border-spacing: 0px">
                                    <div id="contentActivity">
                                        <ul class="nav nav-tabs" style="padding: 0px;">
                                            <li class="active" id="is_tab_comment">
                                                <a href="#" title="Comments">Comments</a>
                                            </li>
                                            <li id="is_tab_history">
                                                <a href="#" title="History">History</a>
                                            </li>
                                            <?php if ($timeTrackingFlag): ?>
                                                <li id="is_tab_work_log">
                                                    <a href="#" title="Work Log">Work Log</a>
                                                </li>
                                            <?php endif ?>
                                        </ul>
                                        <div id="tabContent"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        <?php endif ?>
    </div>

    <?php if ($issueValid): ?>
        <input type="hidden" value="1" id="issue_view_mode" name="issue_view_mode"/>
        <input type="hidden" value="<?php echo $issueId ?>" id="issue_id" name="issue_id"/>
        <input type="hidden" value="<?php echo $loggedInUserId ?>" id="user_id" name="user_id"/>
        <input type="hidden" value="<?php echo $projectId ?>" id="project_id" name="project_id"/>
        <input type="hidden" value="<?php echo $workflowMenuEnabled ?>" id="view_issue_workflow_menu_enabled"/>
        <input type="hidden" value="<?php if ($linkIssueTypes) echo "1"; else echo "0" ?>" id="link_possible"/>
        <?php if ($issue['remaining_estimate']): ?>
        <input type="hidden" value="<?php echo str_replace(" ", '', Util::transformTimeToString(Util::transformLogTimeToMinutes($issue['remaining_estimate'], $hoursPerDay, $daysPerWeek), $hoursPerDay, $daysPerWeek, 'short')); ?>" id="issue_remaining_estimate"/>
        <?php endif ?>
        <input type="hidden" value="<?php echo $workflowUsed['id'] ?>" id="workflow_used_id" name="workflow_used_id"/>
        <input type="hidden" value="<?php echo $issue[Field::FIELD_ASSIGNEE_CODE] ?>" id="issue_ua_id" name="issue_ua_id"/>
        <input type="hidden" value="<?php if (isset($hasEditPermission)) echo "1"; ?>" id="has_edit_permission" />
        <input type="hidden" value="<?php if (isset($hasViewVotersAndWatchersPermission)) echo "1"; ?>" id="has_view_voters_and_watchers_permission" />

        <div id="contentAddIssueWatcher"></div>
    <?php endif ?>

    <div id="deleteIssueModal"></div>
    <div id="transitionIssueModal"></div>
    <div id="deleteComment"></div>
    <div id="addCommentModal"></div>
    <div id="editCommentModal"></div>
    <div id="deleteAttachment"></div>
    <div id="modalAddSubTask"></div>
    <div id="modalEditIssue"></div>
    <div id="modalEditIssueAssign"></div>
    <div id="modalEditIssueAttachFile"></div>
    <div id="duplicateIssueModal"></div>
    <div id="modalProjectFilters"></div>
    <div id="modalLinkIssue"></div>
    <div id="modalLinkIssueDelete"></div>
    <div id="modalLogWork"></div>
    <div id="modalLogWorkEdit"></div>
    <div id="modalLogWorkDelete"></div>
    <div id="modalShareIssue"></div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>