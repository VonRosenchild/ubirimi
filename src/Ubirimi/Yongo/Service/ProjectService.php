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
use Ubirimi\Entity\Yongo\Project as ProjectEntity;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ProjectService extends UbirimiService
{
    public static function add(ProjectEntity $project, $userId)
    {
        $currentDate = $project->getDateCreated();

        $projectId = UbirimiContainer::get()['repository']->get(YongoProject::class)->add(
            $project->getClientId(),
            $project->getIssueTypeSchemeId(),
            $project->getIssueTypeScreenSchemeId(),
            $project->getIssueTypeFieldConfigurationId(),
            $project->getWorkflowSchemeId(),
            $project->getPermissionSchemeId(),
            $project->getNotificationSchemeId(),
            $project->getLeadId(),
            $project->getName(),
            $project->getCode(),
            $project->getDescription(),
            $project->getProjectCategoryId(),
            $project->getHelpDeskEnabledFlag(),
            $project->getDateCreated()
        );

        UbirimiContainer::get()['repository']->get(YongoProject::class)->addDefaultUsers($project->getClientId(), $projectId, $currentDate);
        UbirimiContainer::get()['repository']->get(YongoProject::class)->addDefaultGroups($project->getClientId(), $projectId, $currentDate);

        if ($project->getHelpDeskEnabledFlag()) {

            UbirimiContainer::get()['repository']->get(YongoProject::class)->addDefaultInitialDataForHelpDesk($project->getClientId(), $projectId, $userId, $currentDate);
       }

        return $projectId;
    }
}