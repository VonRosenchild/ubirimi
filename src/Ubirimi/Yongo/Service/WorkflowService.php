<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;
use Ubirimi\Yongo\Repository\Project\Project;

class WorkflowService extends UbirimiService
{
    public function hasEvent($clientId, $projectId, $issueTypeId)
    {
        $workflowUsed = Project::getWorkflowUsedForType($projectId, $issueTypeId);
        $creationData = Workflow::getDataForCreation($workflowUsed['id']);
        $eventData = IssueEvent::getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE);

        return WorkflowFunction::hasEvent($creationData['id'], 'event=' . $eventData['id']);
    }
}
