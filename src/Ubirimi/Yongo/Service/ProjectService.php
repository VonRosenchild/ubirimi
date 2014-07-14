<?php

namespace Ubirimi\Yongo\Service;

use Ubirimi\Entity\Yongo\Project as ProjectEntity;
use Ubirimi\Repository\HelpDesk\SLACalendar;
use Ubirimi\Service\UbirimiService;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Yongo\Repository\Permission\PermissionRole;
use Ubirimi\Repository\HelpDesk\Queue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Repository\HelpDesk\SLA;

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
            $project->getServiceDeskEnabledFlag(),
            $project->getDateCreated()
        );

        Project::addDefaultUsers($project->getClientId(), $projectId, $currentDate);
        Project::addDefaultGroups($project->getClientId(), $projectId, $currentDate);

        if ($project->getServiceDeskEnabledFlag()) {
            // add the default queues
            // -----------------------------------------------
            $defaultColumns = 'code#summary#priority#status#created#updated#reporter#assignee';

            // queue 1: my open tickets
            $queueDefinition = 'assignee=currentUser() AND status = Open AND resolution = Unresolved';
            Queue::save($userId, $projectId, 'My Open Tickets', 'My Open Tickets', $queueDefinition, $defaultColumns, $currentDate);

            // queue 2: need triage
            $queueDefinition = 'status = Open AND resolution = Unresolved';
            Queue::save($userId, $projectId, 'Needs Triage', 'Needs Triage', $queueDefinition, $defaultColumns, $currentDate);

            // queue 3: sla at risk
            $queueDefinition = 'resolution = Unresolved AND (Time waiting for support < 30 AND Time waiting for support > 0 OR Time to resolution < 30 AND Time to resolution > 0)';
            Queue::save($userId, $projectId, 'SLA at risk', 'SLA at risk', $queueDefinition, $defaultColumns, $currentDate);

            // queue 4: sla at risk
            $queueDefinition = 'resolution = Unresolved AND (Time waiting for support < 0 OR Time to resolution < 0)';
            Queue::save($userId, $projectId, 'SLA breached', 'SLA breached', $queueDefinition, $defaultColumns, $currentDate);

            // add the default SLA calendar
            $dataDefaultCalendar = array();
            for ($i = 0; $i < 7; $i++) {
                $dataDefaultCalendar[$i]['notWorking'] = 0;
                $dataDefaultCalendar[$i]['from_hour'] = '00';
                $dataDefaultCalendar[$i]['from_minute'] = '00';
                $dataDefaultCalendar[$i]['to_hour'] = '23';
                $dataDefaultCalendar[$i]['to_minute'] = '59';
            }

            $defaultSLACalendarId = SLACalendar::addCalendar($projectId, 'Default 24/7 Calendar', 'Default 24/7 Calendar', $dataDefaultCalendar, $currentDate);

            // add the default SLAs
            // --------------------------------------------------------

            // sla 1: time to first response
            $status = IssueSettings::getByName($project->getClientId(), 'status', 'In Progress');
            $slaId = SLA::save($projectId, 'Time to first response', 'Time to first response', 'start_issue_created', 'stop_status_set_' . $status['id'], $currentDate);

            // sla 2: time to resolution
            $slaId = SLA::save($projectId, 'Time to resolution', 'Time to resolution', 'start_issue_created', 'stop_resolution_set', $currentDate);
            SLA::addGoal($slaId, $defaultSLACalendarId, 'priority = Blocker', '', 1440);

            // sla 3: time waiting for support
            $slaId = SLA::save($projectId, 'Time waiting for support', 'Time waiting for support', 'start_issue_created', 'stop_resolution_set', $currentDate);
            SLA::addGoal($slaId, $defaultSLACalendarId, 'priority = Blocker', '', 24);
            SLA::addGoal($slaId, $defaultSLACalendarId, 'priority = Critical', '', 96);
        }

        return $projectId;
    }
}