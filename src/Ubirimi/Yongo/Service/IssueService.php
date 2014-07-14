<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\Yongo\Repository\Issue\IssueComment;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

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

        $issueReturnValues = Issue::add(
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
        Project::updateLastIssueNumber($projectId, $newIssueNumber);

        // if a parent is set check if the parent issue id is part of a sprint. if yes also add the child
        if ($issueId) {
            $sprints = AgileSprint::getByIssueId($clientId, $issueId);
            while ($sprints && $sprint = $sprints->fetch_array(MYSQLI_ASSOC)) {
                AgileSprint::addIssues($sprint['id'], array($newIssueId), $loggedInUserId);
            }
        }

        // check if on the modal there is a comment field
        if (array_key_exists(Field::FIELD_COMMENT_CODE, $issueSystemFieldsData)) {
            $comment = Util::cleanRegularInputField($issueSystemFieldsData[Field::FIELD_COMMENT_CODE]);
            if (!empty($comment)) {
                IssueComment::add($newIssueId, $loggedInUserId, $comment, $currentDate);
            }
        }

        // save custom fields
        if (count($issueCustomFieldsData)) {
            IssueCustomField::saveCustomFieldsData(
                $newIssueId,
                $issueCustomFieldsData,
                $currentDate
            );
        }

        if (isset($issueSystemFieldsData['component'])) {
            Issue::addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['component'],
                'issue_component'
            );
        }

        if (isset($issueSystemFieldsData['affects_version'])) {
            Issue::addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['affects_version'],
                'issue_version',
                Issue::ISSUE_AFFECTED_VERSION_FLAG
            );
        }

        if (isset($issueSystemFieldsData['fix_version'])) {
            Issue::addComponentVersion(
                $newIssueId,
                $issueSystemFieldsData['fix_version'],
                'issue_version',
                Issue::ISSUE_FIX_VERSION_FLAG
            );
        }

        Util::manageModalAttachments($newIssueId, $loggedInUserId, $attachIdsToBeKept);

        $issue = Issue::getById($newIssueId);

        // add the current logged in user to the list of watchers
        IssueWatcher::addWatcher($newIssueId, $loggedInUserId, $currentDate);

        // add sla information for this issue
        Issue::addPlainSLAData($newIssueId, $projectId);
        $issueSLAData = Issue::updateSLAValue($issue, $clientId, $clientSettings);

        return $issue;
    }
}
