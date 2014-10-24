<?php

namespace Ubirimi\Yongo\Controller\Project\Report;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class ViewChartStatisticTypeController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $loggedInUserId = $session->get('user/id');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $loggedInUserId = null;
            $clientId = $this->getRepository(UbirimiClient::class)->getClientIdAnonymous();
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
        }

        $projectId = $request->get('id');
        $project = $this->getRepository(YongoProject::class)->getById($projectId);

        if ($project['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $statisticType = Util::cleanRegularInputField($request->get('statistic_type'));
        $chartType = Util::cleanRegularInputField($request->get('chart_type'));

        $issueQueryParameters = array('project' => array($projectId));
        $issues = $this->getRepository(Issue::class)->getByParameters($issueQueryParameters, $loggedInUserId, null, $loggedInUserId);

        if ($statisticType == 'assignee') {
            $issuesAssignee = array();
            if ($issues) {
                $totalIssues = $issues->num_rows;
                while ($issues && $issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                    if (!array_key_exists($issue['assignee'], $issuesAssignee)) {
                        $issuesAssignee[$issue['assignee']] = array('assignee_name' => $issue['ua_first_name'] . ' ' . $issue['ua_last_name'],
                            'issues_count' => 0);
                    }
                    $issuesAssignee[$issue['assignee']]['issues_count']++;
                }
            }
        }

        $hasGlobalAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = $this->getRepository(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);
        $hasAdministerProjectsPermission = $this->getRepository(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);

        $hasAdministerProject = $hasGlobalSystemAdministrationPermission || $hasGlobalAdministrationPermission || $hasAdministerProjectsPermission;

        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / ' . $project['name'] . ' / Reports / Chart';

        $menuProjectCategory = 'reports';
        $menuSelectedCategory = 'project';

        return $this->render(__DIR__ . '/../../../Resources/views/project/report/ViewChartStatisticType.php', get_defined_vars());
    }
}
