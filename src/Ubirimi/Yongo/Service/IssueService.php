<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\CustomField;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Yongo\Repository\Issue\Watcher;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class IssueService extends UbirimiService
{
    public function save(
        $project,
        $issueSystemFieldsData,
        $issueId,
        $timeTrackingDefaultUnit,
        $projectId,
        $issueCustomFieldsData,
        $attachIdsToBeKept,
        $clientSettings,
        $loggedInUserId,
        $clientId
    )
    {
        $currentDate = Util::getServerCurrentDateTime();

        $issueReturnValues = UbirimiContainer::get()['repository']->get(Issue::class)->add(
            $project,
            $currentDate,
            $issueSystemFieldsData,
            $loggedInUserId,
            $issueId,
            $timeTrackingDefaultUnit
        );

        $newIssueId = $issueReturnValues[0];
        $newIssueNumber = $issueReturnValues[1];

        // update last issue number for this project
        UbirimiContainer::get()['repository']->get(YongoProject::class)->updateLastIssueNumber($projectId, $newIssueNumber);

        // if a parent is set check if the parent issue id is part of a sprint. if yes also add the child
        if ($issueId) {
            $sprints = UbirimiContainer::get()['repository']->get('agile.sprint.sprint')->getByIssueId($clientId, $issueId);
            while ($sprints && $sprint = $sprints->fetch_array(MYSQLI_ASSOC)) {
                UbirimiContainer::get()['repository']->get('agile.sprint.sprint')->addIssues($sprint['id'], array($newIssueId), $loggedInUserId);
            }
        }

        // check if on the modal there is a comment field
        if (array_key_exists(Field::FIELD_COMMENT_CODE, $issueSystemFieldsData)) {
            $comment = Util::cleanRegularInputField($issueSystemFieldsData[Field::FIELD_COMMENT_CODE]);
            if (!empty($comment)) {
                UbirimiContainer::get()['repository']->get(IssueComment::class)->add($newIssueId, $loggedInUserId, $comment, $currentDate);
            }
        }

        // save custom fields
        if (count($issueCustomFieldsData)) {
            UbirimiContainer::get()['repository']->get(CustomField::class)->saveCustomFieldsData(
                $newIssueId,
                $issueCustomFieldsData,
                $currentDate
            );
        }

        if (isset($issueSystemFieldsData['component'])) {
            UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['component'],
                'issue_component'
            );
        }

        if (isset($issueSystemFieldsData['affects_version'])) {
            UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['affects_version'],
                'issue_version',
                Issue::ISSUE_AFFECTED_VERSION_FLAG
            );
        }

        if (isset($issueSystemFieldsData['fix_version'])) {
            UbirimiContainer::get()['repository']->get(Issue::class)->addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['fix_version'],
                'issue_version',
                Issue::ISSUE_FIX_VERSION_FLAG
            );
        }

        Util::manageModalAttachments($newIssueId, $loggedInUserId, $attachIdsToBeKept);

        $issue = UbirimiContainer::get()['repository']->get(Issue::class)->getById($newIssueId);

        // add the current logged in user to the list of watchers
        UbirimiContainer::get()['repository']->get(Watcher::class)->add($newIssueId, $loggedInUserId, $currentDate);

        // add sla information for this issue
        UbirimiContainer::get()['repository']->get(Issue::class)->addPlainSLAData($newIssueId, $projectId);

        return $issue;
    }
}
