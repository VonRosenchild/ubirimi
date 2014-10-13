<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\Event;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;
use Ubirimi\Yongo\Repository\Project\Project;

class WorkflowService extends UbirimiService
{
    public function hasEvent($clientId, $projectId, $issueTypeId)
    {
        $workflowUsed = $this->getRepository('yongo.project.project')->getWorkflowUsedForType($projectId, $issueTypeId);
        $creationData = $this->getRepository('yongo.workflow.workflow')->getDataForCreation($workflowUsed['id']);
        $eventData = Event::getByClientIdAndCode($clientId, Event::EVENT_ISSUE_CREATED_CODE);

        return WorkflowFunction::hasEvent($creationData['id'], 'event=' . $eventData['id']);
    }
}
