<?php

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