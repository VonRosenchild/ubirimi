<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Component;
use Ubirimi\Yongo\Repository\Issue\Version;
use Ubirimi\Yongo\Repository\Project\Project;

class DuplicateController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->get('issue_id');

        $summary = $request->get('summary');
        $oldIssueData = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);
        $project = $this->getRepository('yongo.project.project')->getById($oldIssueData['issue_project_id']);

        $currentDate = Util::getServerCurrentDateTime();
        $issueSystemFields = array('reporter' => $oldIssueData[Field::FIELD_REPORTER_CODE], 'summary' => $summary, 'priority' => $oldIssueData['priority'],
            'assignee' => $oldIssueData['assignee'], 'description' => $oldIssueData['description'], Field::FIELD_DUE_DATE_CODE => $oldIssueData['due_date'],
            'environment' => $oldIssueData['environment'], 'type' => $oldIssueData['type']);

        $issueReturnValues = Issue::add($project, $currentDate, $issueSystemFields, $loggedInUserId);
        $issueId = $issueReturnValues[0];

        $components = Component::getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id']);
        if ($components) {
            $components_arr = array();
            while ($component = $components->fetch_array(MYSQLI_ASSOC))
                $components_arr[] = $component['project_component_id'];

            Issue::addComponentVersion($issueId, $components_arr, 'issue_component');
        }

        $versions = Version::getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id'], Issue::ISSUE_AFFECTED_VERSION_FLAG);
        if ($versions) {
            $versions_arr = array();
            while ($version = $versions->fetch_array(MYSQLI_ASSOC))
                $versions_arr[] = $version['project_version_id'];

            Issue::addComponentVersion($issueId, $versions_arr, 'issue_version', Issue::ISSUE_AFFECTED_VERSION_FLAG);
        }

        $targets = Version::getByIssueIdAndProjectId($oldIssueData['id'], $oldIssueData['issue_project_id'], Issue::ISSUE_FIX_VERSION_FLAG);
        if ($targets) {
            $targets_arr = array();
            while ($target = $targets->fetch_array(MYSQLI_ASSOC))
                $targets_arr[] = $target['project_version_id'];

            Issue::addComponentVersion($issueId, $targets_arr, 'issue_version', Issue::ISSUE_FIX_VERSION_FLAG);
        }

        return new Response($issueId);
    }
}
