<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewVersionIssuesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $loggedInUserId = null;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        }

        $versionId = $request->get('id');
        $version = $this->getRepository('yongo.project.project')->getVersionById($versionId);
        $projectId = $version['project_id'];
        $project = $this->getRepository('yongo.project.project')->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $issueQueryParameters = array('project' => $projectId, 'resolution' => array(-2), 'version' => $versionId);
        $issues = $this->getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);

        $count = 0;

        $statsPriority = array();
        $statsType = array();
        $statsAssignee = array();
        $statsStatus = array();

        if ($issues) {
            $count = $issues->num_rows;

            // group them by priority

            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsPriority[$issue['priority']])) {
                    $statsPriority[$issue['priority']] = array($issue['priority_name'] => 0);
                }
                $statsPriority[$issue['priority']][$issue['priority_name']]++;
            }

            // group them by type
            $issues->data_seek(0);

            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsType[$issue['type']])) {
                    $statsType[$issue['type']] = array($issue['type_name'] => 0);
                }
                $statsType[$issue['type']][$issue['type_name']]++;
            }

            // group them by status
            $issues->data_seek(0);

            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsStatus[$issue['status']])) {
                    $statsStatus[$issue['status']] = array($issue['status_name'] => 0);
                }
                $statsStatus[$issue['status']][$issue['status_name']]++;
            }

            // group them by assignee
            $issues->data_seek(0);

            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                if (!isset($statsAssignee[$issue['assignee']])) {
                    $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
                    $statsAssignee[$issue['assignee']] = array($userName => 0);
                }
                $userName = $issue['ua_first_name'] . ' ' . $issue['ua_last_name'];
                $statsAssignee[$issue['assignee']][$userName]++;
            }
        }

        $menuSelectedCategory = 'project';

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Version: ' . $version['name'] . ' / Issues Summary';

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewVersionIssues.php', get_defined_vars());
    }
}
