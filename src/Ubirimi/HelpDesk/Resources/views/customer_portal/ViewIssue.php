<?php
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

require_once __DIR__ . '/_header.php';
?>
<body style="background-color: #EEEEEE ;">

    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php if (!$issueValid): ?>
        <div class="infoBox">This issue does not exist or you do not have the permission to view it.</div>
    <?php else: ?>
        <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/_titleSummary.php' ?>
    <?php endif ?>

    <div class="pageContent">
        <?php if ($issueValid): ?>
            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/_topButtons.php' ?>

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
                            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/_sectionDetailsSlas.php' ?>
                        <?php endif ?>
                        <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/_sectionDetailsDates.php' ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/_mainInformation.php' ?>

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

                            <?php if ($attachments): ?>
                                <?php Util::includePartial(__DIR__ . '/../../../../Yongo/Resources/views/issue/_attachments.php', array('attachments' => $attachments,
                                                                                                                                'loggedInUserId' => $loggedInUserId,
                                                                                                                                'hasDeleteAllAttachmentsPermission' => $hasDeleteAllAttachmentsPermission,
                                                                                                                                'hasDeleteOwnAttachmentsPermission' => $hasDeleteOwnAttachmentsPermission)); ?>
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

    <input type="hidden" value="<?php echo $issueId ?>" id="issue_id" name="issue_id"/>
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" name="project_id"/>
    <input type="hidden" value="1" id="issue_view_mode" name="issue_view_mode"/>
    <?php if ($issueValid): ?>
        <input type="hidden" value="<?php echo $workflowMenuEnabled ?>" id="view_issue_workflow_menu_enabled"/>
        <input type="hidden" value="<?php echo $workflowUsed['id'] ?>" id="workflow_used_id" name="workflow_used_id"/>
        <input type="hidden" value="<?php echo $issue[Field::FIELD_ASSIGNEE_CODE] ?>" id="issue_ua_id" name="issue_ua_id"/>
        <input type="hidden" value="<?php if (isset($hasEditPermission)) echo "1"; ?>" id="has_edit_permission" />
    <?php endif ?>

    <div id="deleteIssueModal" class="ubirimiModalDialog"></div>
    <div id="transitionIssueModal" class="ubirimiModalDialog"></div>
    <div id="deleteComment"></div>
    <div id="addCommentModal" class="ubirimiModalDialog"></div>
    <div id="editCommentModal" class="ubirimiModalDialog"></div>
    <div id="deleteAttachment"></div>
    <div class="ubirimiModalDialog" id="modalAddSubTask"></div>
    <div class="ubirimiModalDialog" id="modalEditIssue"></div>
    <div class="ubirimiModalDialog" id="modalEditIssueAssign"></div>
    <div class="ubirimiModalDialog" id="modalEditIssueAttachFile"></div>
    <div id="duplicateIssueModal" class="ubirimiModalDialog"></div>
    <div class="ubirimiModalDialog" id="modalProjectFilters"></div>
    <div class="ubirimiModalDialog" id="modalLinkIssue"></div>
    <div class="ubirimiModalDialog" id="modalLinkIssueDelete"></div>
    <div class="ubirimiModalDialog" id="modalLogWork"></div>
    <div class="ubirimiModalDialog" id="modalLogWorkEdit"></div>
    <div class="ubirimiModalDialog" id="modalLogWorkDelete"></div>
    <div class="ubirimiModalDialog" id="modalShareIssue"></div>
</body>