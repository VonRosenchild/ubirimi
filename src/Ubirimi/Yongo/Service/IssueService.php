<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Repository\Client;
use Ubirimi\Service\DatabaseConnector;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueCustomField;
use Ubirimi\Yongo\Repository\Issue\IssueWatcher;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Agile\Repository\AgileSprint;
use Ubirimi\Yongo\Repository\Issue\IssueComment;

class IssueService
{
    private $connection;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function save(
        $clientId,
        $loggedInUserId,
        $currentDate,
        $projectId,
        $typeId,
        $reporter,
        $timeTrackingOriginalEstimate,
        $timeTrackingRemainingEstimate,
        $priority,
        $assignee,
        $summary,
        $description,
        $comment,
        $environment,
        $dueDate,
        $parentId,
        $securityLevel,
        $component,
        $affectsVersion,
        $fixVersion,
        $customFields
    )
    {
        $issueNumber = Issue::getAvailableIssueNumber($projectId);
        $workflowUsed = Project::getWorkflowUsedForType($projectId, $typeId);

        $statusData = Workflow::getDataForCreation($workflowUsed['id']);
        $statusId = $statusData['linked_issue_status_id'];

        $query = "INSERT INTO yongo_issue(project_id, priority_id, status_id, type_id, user_assigned_id, user_reported_id, nr, " .
            "summary, description, environment, date_created, date_due, parent_id, security_scheme_level_id, original_estimate, remaining_estimate) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($query);

        $stmt->bind_param("iiiiiiisssssiiss",
            $projectId,
            $priority,
            $statusId,
            $typeId,
            $assignee,
            $reporter,
            $issueNumber,
            $summary,
            $description,
            $environment,
            $currentDate,
            $dueDate,
            $parentId,
            $securityLevel,
            $timeTrackingRemainingEstimate,
            $timeTrackingOriginalEstimate
        );

        $stmt->execute();

        $newIssueId = $this->connection->insert_id;

        // update last issue number for this project
        Project::updateLastIssueNumber($projectId, $issueNumber);

        // if a parent is set check if the parent issue id is part of a sprint. if yes also add the child
        if ($parentId) {
            $sprints = AgileSprint::getByIssueId($clientId, $parentId);
            while ($sprints && $sprint = $sprints->fetch_array(MYSQLI_ASSOC)) {
                AgileSprint::addIssues($sprint['id'], array($newIssueId), $loggedInUserId);
            }
        }

        IssueComment::add($newIssueId, $loggedInUserId, $comment, $currentDate);

        // save custom fields
        if (count($customFields)) {
            IssueCustomField::saveCustomFieldsData($newIssueId, $customFields, $currentDate);
        }

        if ($component) {
            Issue::addComponentVersion($newIssueId, $component, 'issue_component');
        }

        if ($affectsVersion) {
            Issue::addComponentVersion($newIssueId, $affectsVersion, 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        if (isset($fixVersion)) {
            Issue::addComponentVersion($newIssueId, $fixVersion, 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        // add the current logged in user to the list of watchers
        IssueWatcher::addWatcher($newIssueId, $loggedInUserId, $currentDate);

        // add SLA information for this issue
        Issue::addPlainSLAData($newIssueId, $projectId);

        $issue = Issue::getById($newIssueId);

        Issue::updateSLAValue($issue, $clientId, Client::getSettings($clientId));
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}