<?php

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;

use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Settings;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $issuesPerPage = $session->get('user/issues_per_page');
            $clientSettings = $session->get('client/settings');
        } else {
            $clientId = $this->getRepository('ubirimi.general.client')->getClientIdAnonymous();
            $issuesPerPage = 25;
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
        }
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Dashboard';

        $userAssignedId = $session->get('user/id');
        $allProjects = $this->getRepository('ubirimi.general.client')->getProjects($clientId);

        $projects = $this->getRepository('ubirimi.general.client')->getProjectsByPermission(
            $clientId,
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $projectIdsArray = array();
        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsArray[] = $projects[$i]['id'];
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        $issueQueryParameters = array(
            'issues_per_page' => $issuesPerPage,
            'assignee' => $userAssignedId,
            'resolution' => array(-2),
            'sort' => 'code',
            'sort_order' => 'desc',
            'date_created_after' => date('Y-m-d H:i:s', strtotime("-90 days"))
        );

        if (count($projectIdsArray)) {
            $issueQueryParameters['project'] = $projectIdsArray;
        } else {
            $issueQueryParameters['project'] = array(-1);
        }

        $issues = $this->getRepository('yongo.issue.issue')->getByParameters(
            $issueQueryParameters,
            $session->get('user/id'),
            null,
            $session->get('user/id')
        );

        $issueQueryParameters = array(
            'issues_per_page' => $issuesPerPage,
            'resolution' => array(-2),
            'sort' => 'code',
            'sort_order' => 'desc',
            'date_created_after' => date('Y-m-d H:i:s', strtotime("-90 days"))
        );

        if (count($projectIdsArray)) {
            $issueQueryParameters['project'] = $projectIdsArray;
        }

        if ($session->get('user/id')) {
            $issueQueryParameters['not_assignee'] = $userAssignedId;
        }

        $issuesUnresolvedOthers = $this->getRepository('yongo.issue.issue')->getByParameters(
            $issueQueryParameters,
            $session->get('user/id'),
            null,
            $session->get('user/id')
        );

        $menuSelectedCategory = 'home';

        $hasGlobalAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission(
            $clientId,
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS
        );

        $hasGlobalSystemAdministrationPermission = $this->getRepository('ubirimi.user.user')->hasGlobalPermission(
            $clientId,
            $session->get('user/id'),
            GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS
        );

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        $usersAsAssignee = $this->getRepository('ubirimi.user.user')->getByClientId($clientId);
        $issueStatuses = $this->getRepository('yongo.issue.settings')->getAllIssueSettings('status', $clientId, 'array');
        $twoDimensionalData = null;
        if (count($projectIdsArray))
            $twoDimensionalData = $this->getRepository('yongo.issue.issue')->get2DimensionalFilter(-1, 'array');

        return $this->render(__DIR__ . '/../Resources/views/Index.php', get_defined_vars());
    }
}
