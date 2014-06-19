<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class WorkflowService extends UbirimiService
{
    public function hasEvent($clientId, $projectId, $issueTypeId)
    {
        $workflowUsed = $this->apiClient->get(
            sprintf('/yongo/workflow/project/%d/issue_type/%d', $projectId, $issueTypeId)
        )->getContent();

        $initialStep = $this->apiClient->get(
            sprintf('/yongo/workflow/initial_step?workflowId=%d', $workflowUsed[0]['id'])
        )->getContent();

        $creationData = $this->apiClient->get(
            sprintf('/yongo/workflow/creation_data?workflowId=%d&stepId=%d', $workflowUsed[0]['id'], $initialStep[0]['id'])
        )->getContent();

        $eventData = $this->apiClient->get(
            sprintf('/yongo/event/find/clientId/%d/code/%d', $clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE)
        )->getContent();

        return WorkflowFunction::hasEvent($creationData[0]['id'], 'event=' . $eventData[0]['id']);
    }
}