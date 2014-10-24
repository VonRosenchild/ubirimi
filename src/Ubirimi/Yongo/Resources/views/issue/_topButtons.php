<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

?>

<table cellspacing="0" border="0" cellpadding="0" width="100%">
    <tr>
        <td>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <?php if ($hasEditPermission && $issueEditableProperty): ?>
                        <td><a id="btnEditIssueFromDetail" href="#" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
                    <?php endif ?>
                    <?php if ($hasAssignPermission && $issueEditableProperty): ?>
                        <td><a id="btnEditIssueAssign" href="#" class="btn ubirimi-btn">Assign</a></td>
                    <?php endif ?>
                    <?php if ($hasAssignPermission && $hasAssignablePermission && $issue['assignee'] != $loggedInUserId): ?>
                        <td><a id="btnIssueAssignToMe" href="#" class="btn ubirimi-btn">Assign To Me</a></td>
                    <?php endif ?>
                    <?php if ($hasAddCommentsPermission): ?>
                        <td>
                            <a id="btnEditIssueComment" href="#" class="btn ubirimi-btn"><i class="icon-comment"></i> Comment</a>
                        </td>
                    <?php endif ?>
                    <td>
                        <?php
                            Util::includePartial(__DIR__ . '/_moreActionMenu.php', array('issueEditableProperty' => $issueEditableProperty,
                                                                                               'issue' => $issue,
                                                                                               'hasCreatePermission' => isset($hasCreatePermission) ? $hasCreatePermission : null,
                                                                                               'hasLinkIssuePermission' => $hasLinkIssuePermission,
                                                                                               'parentIssue' => $parentIssue,
                                                                                               'timeTrackingFlag' => $timeTrackingFlag,
                                                                                               'subTaskIssueTypes' => isset($subTaskIssueTypes ) ? $subTaskIssueTypes : null,
                                                                                               'issueLinkingFlag' => isset($issueLinkingFlag ) ? $issueLinkingFlag : null,
                                                                                               'hasMoveIssuePermission' => isset($hasMoveIssuePermission ) ? $hasMoveIssuePermission : null,
                                                                                               'hasWorkOnIssuePermission' => isset($hasWorkOnIssuePermission ) ? $hasWorkOnIssuePermission : null,
                                                                                               'hasCreateAttachmentPermission' => isset($hasCreateAttachmentPermission) ? $hasCreateAttachmentPermission : null));

                        ?>
                    </td>
                    <td><a id="btnPrint" target="_blank" href="/yongo/issue/print/<?php echo $issue['id'] ?>" class="btn ubirimi-btn"><i class="icon-print"></i> Print</a></td>
                    <?php if ($hasDeletePermission): ?>
                        <td>
                            <a id="btnDeleteIssueDetail" href="#" class="btn ubirimi-btn">
                                <i class="icon-remove"></i> Delete
                            </a>
                        </td>
                    <?php endif ?>
                    <?php
                        $buttonsCount = 0;
                        while ($workflowActions && $workflowStep = $workflowActions->fetch_array(MYSQLI_ASSOC)) {
                            $workflowDataId = $workflowStep['id'];
                            $transitionEvent = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getEventByWorkflowDataId($workflowDataId);
                            $hasEventPermission = false;

                            switch ($transitionEvent['code']) {

                                case IssueEvent::EVENT_ISSUE_CLOSED_CODE:

                                    $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_CLOSE_ISSUE, $loggedInUserId);
                                    break;
                                case IssueEvent::EVENT_ISSUE_REOPENED_CODE:

                                case IssueEvent::EVENT_ISSUE_RESOLVED_CODE:
                                    $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_RESOLVE_ISSUE, $loggedInUserId);
                                    break;
                                case IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE:
                                case IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE:

                                    $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $loggedInUserId);
                                    break;

                                case IssueEvent::EVENT_GENERIC_CODE:
                                    $hasEventPermission = true;
                                    break;
                            }

                            $canBeExecuted = UbirimiContainer::get()['repository']->get(Workflow::class)->checkConditionsByTransitionId($workflowStep['id'], $loggedInUserId, $issue);
                            if ($workflowStep['screen_id']) {
                                if ($hasEventPermission && $canBeExecuted) {
                                    echo '<td><a href="#" id="issue_transition_with_screen_' . $step['id'] . '_' . $workflowStep['workflow_step_id_to'] . '" class="btn ubirimi-btn">' . $workflowStep['transition_name'] . '</a></td>';
                                    $buttonsCount++;
                                }
                            } else {
                                if ($hasEventPermission && $canBeExecuted) {
                                    echo '<td><a href="#" id="issue_transition_with_no_screen_' . $step['id'] . '_' . $workflowStep['workflow_step_id_to'] . '" class="btn ubirimi-btn">' . $workflowStep['transition_name'] . '</a></td>';
                                    $buttonsCount++;
                                }
                            }

                            if ($buttonsCount == 2) {
                                break;

                            }
                        }
                    ?>
                    <td>
                        <div class="btn-group">
                            <ul class="dropdown-menu">
                                <?php $workflowMenuEnabled = 0; ?>
                                <?php while ($workflowActions && $workflowStep = $workflowActions->fetch_array(MYSQLI_ASSOC)): ?>

                                    <?php $canBeExecuted = UbirimiContainer::get()['repository']->get(Workflow::class)->checkConditionsByTransitionId($workflowStep['id'], $loggedInUserId, $issue); ?>
                                    <?php if ($canBeExecuted)
                                        $workflowMenuEnabled = 1; ?>
                                    <?php
                                    $workflowDataId = $workflowStep['id'];
                                    $transitionEvent = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getEventByWorkflowDataId($workflowDataId);
                                    $hasEventPermission = false;

                                    switch ($transitionEvent['code']) {

                                        case IssueEvent::EVENT_ISSUE_CLOSED_CODE:

                                            $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_CLOSE_ISSUE, $loggedInUserId);
                                            break;

                                        case IssueEvent::EVENT_ISSUE_REOPENED_CODE:
                                        case IssueEvent::EVENT_ISSUE_RESOLVED_CODE:

                                        $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_RESOLVE_ISSUE, $loggedInUserId);
                                            break;
                                        case IssueEvent::EVENT_ISSUE_WORK_STARTED_CODE:
                                        case IssueEvent::EVENT_ISSUE_WORK_STOPPED_CODE:
                                            $hasEventPermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectId, Permission::PERM_EDIT_ISSUE, $loggedInUserId);
                                            break;
                                        case IssueEvent::EVENT_GENERIC_CODE:
                                            $hasEventPermission = true;
                                            break;
                                    }
                                    ?>

                                    <li>
                                        <?php if ($workflowStep['screen_id']): ?>
                                            <?php if ($canBeExecuted && $hasEventPermission): ?>
                                                <a id="issue_transition_with_screen_<?php echo $step['id'] . '_' . $workflowStep['workflow_step_id_to']; ?>" href="#"><?php echo $workflowStep['transition_name'] ?></a>
                                            <?php endif ?>
                                        <?php else: ?>
                                            <?php if ($canBeExecuted && $hasEventPermission): ?>
                                                <a id="issue_transition_with_no_screen_<?php echo $step['id'] . '_' . $workflowStep['workflow_step_id_to']; ?>" href="#"><?php echo $workflowStep['transition_name'] ?></a>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </li>
                                <?php endwhile ?>
                            </ul>
                            <a class="btn ubirimi-btn dropdown-toggle <?php if (0 == $workflowMenuEnabled): ?>disabled<?php endif ?>" data-toggle="dropdown" href="#">
                                Workflow <span class="caret"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td align="right">
            <?php if (Util::checkUserIsLoggedIn()): ?>
                <a class="btn ubirimi-btn" id="btnShareIssue" href="#">
                    <i class="icon-share"></i> Share
                </a>
            <?php endif ?>

            <?php if ($arrayListResultIds): ?>
                <?php if ($session->has('last_search_parameters')): ?>
                    <?php if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO): ?>
                        <a class="btn ubirimi-btn" href="/yongo/issue/search?<?php echo $session->get('last_search_parameters') ?>">Return to Search</a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/helpdesk/customer-portal/tickets?<?php echo $session->get('last_search_parameters') ?>">Return to Search</a>
                    <?php endif ?>
                <?php endif ?>
                <?php if (($index - 1) >= 0 && $arrayListResultIds[$index - 1]): ?>
                    <?php if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO): ?>
                        <a class="btn ubirimi-btn" href="/yongo/issue/<?php echo $arrayListResultIds[$index - 1] ?>">
                            <img border="0" height="10" src="/img/br_prev.png"/> Previous</a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/helpdesk/customer-portal/ticket/<?php echo $arrayListResultIds[$index - 1] ?>">
                            <img border="0" height="10" src="/img/br_prev.png"/> Previous</a>
                    <?php endif ?>
                <?php else: ?>
                    <a class="btn ubirimi-btn disabled" href="#"><img border="0" height="10" src="/img/br_prev.png"/> Previous</a>
                <?php endif ?>
                <?php if ((($index + 1) < count($arrayListResultIds)) && $arrayListResultIds[$index + 1]): ?>
                    <?php if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO): ?>
                        <a class="btn ubirimi-btn" href="/yongo/issue/<?php echo $arrayListResultIds[$index + 1] ?>">Next <img border="0" height="10" src="/img/br_next.png"/></a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/helpdesk/customer-portal/ticket/<?php echo $arrayListResultIds[$index + 1] ?>">Next <img border="0" height="10" src="/img/br_next.png"/></a>
                    <?php endif ?>
                <?php else: ?>
                    <a class="btn ubirimi-btn disabled" href="#">Next <img border="0" height="10" src="/img/br_next.png"/></a>
                <?php endif ?>
            <?php endif ?>
        </td>
    </tr>
</table>