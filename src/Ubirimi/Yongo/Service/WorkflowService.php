<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Yongo\Service;

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

class WorkflowService extends UbirimiService
{
    public function hasEvent($clientId, $projectId, $issueTypeId)
    {
        $workflowUsed = UbirimiContainer::get()['repository']->get(YongoProject::class)->getWorkflowUsedForType($projectId, $issueTypeId);
        $creationData = UbirimiContainer::get()['repository']->get(Workflow::class)->getDataForCreation($workflowUsed['id']);
        $eventData = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClientIdAndCode($clientId, IssueEvent::EVENT_ISSUE_CREATED_CODE);

        return UbirimiContainer::get()['repository']->get(WorkflowFunction::class)->hasEvent($creationData['id'], 'event=' . $eventData['id']);
    }
}
