<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

class WorkflowService extends UbirimiService
{
    public function hasEvent($clientId, $projectId, $issueTypeId)
    {
        $workflowUsed = UbirimiContainer::get()['repository']->get(YongoProject::class)->getWorkflowUsedForType($projectId, $issueTypeId);
        $creationData = UbirimiContainer::get()['repository']->get(Workflow::class)->getDataForCreation($workflowUsed['id']);
        $eventData = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE);

        return UbirimiContainer::get()['repository']->get('yongo.workflow.workflowFunction')->hasEvent($creationData['id'], 'event=' . $eventData['id']);
    }
}
