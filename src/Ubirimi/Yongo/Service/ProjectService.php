<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Entity\Yongo\Project as ProjectEntity;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Project\Project;

class ProjectService extends UbirimiService
{
    public static function add(ProjectEntity $project, $userId)
    {
        $currentDate = $project->getDateCreated();

        $projectId = Project::add(
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

        Project::addDefaultUsers($project->getClientId(), $projectId, $currentDate);
        Project::addDefaultGroups($project->getClientId(), $projectId, $currentDate);

        if ($project->getHelpDeskEnabledFlag()) {

            Project::addDefaultInitialDataForHelpDesk($project->getClientId(), $projectId, $userId, $currentDate);
       }

        return $projectId;
    }
}