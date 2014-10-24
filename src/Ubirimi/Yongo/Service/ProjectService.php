<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Entity\Yongo\Project as ProjectEntity;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ProjectService extends UbirimiService
{
    public static function add(ProjectEntity $project, $userId)
    {
        $currentDate = $project->getDateCreated();

        $projectId = $this->getRepository(YongoProject::class)->add(
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

        $this->getRepository(YongoProject::class)->addDefaultUsers($project->getClientId(), $projectId, $currentDate);
        $this->getRepository(YongoProject::class)->addDefaultGroups($project->getClientId(), $projectId, $currentDate);

        if ($project->getHelpDeskEnabledFlag()) {

            $this->getRepository(YongoProject::class)->addDefaultInitialDataForHelpDesk($project->getClientId(), $projectId, $userId, $currentDate);
       }

        return $projectId;
    }
}