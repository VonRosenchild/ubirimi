<?php

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\Repository\User\User;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

class IndexController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $clientId = $session->get('client/id');
            $issuesPerPage = $session->get('user/issues_per_page');
            $clientSettings = $session->get('client/settings');;
        } else {
            $clientId = Client::getClientIdAnonymous();
            $issuesPerPage = 25;
            $clientSettings = Client::getSettings($clientId);
        }
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Dashboard';

        $userAssignedId = $session->get('user/id');
        $allProjects = Client::getProjects($clientId);

        $projects = Client::getProjectsByPermission($clientId, $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');

        $projectIdsArray = array();
        $projectIdsNames = array();
        for ($i = 0; $i < count($projects); $i++) {
            $projectIdsArray[] = $projects[$i]['id'];
            $projectIdsNames[] = array($projects[$i]['id'], $projects[$i]['name']);
        }

        $issueQueryParameters = array('issues_per_page' => $issuesPerPage, 'assignee' => $userAssignedId, 'resolution' => array(-2), 'sort' => 'code', 'sort_order' => 'desc');
        if (count($projectIdsArray)) {
            $issueQueryParameters['project'] = $projectIdsArray;
        } else {
            $issueQueryParameters['project'] = array(-1);
        }
        $issues = Issue::getByParameters($issueQueryParameters, $session->get('user/id'), null, $session->get('user/id'));

        $issueQueryParameters = array('issues_per_page' => $issuesPerPage, 'resolution' => array(-2), 'sort' => 'code', 'sort_order' => 'desc');
        if (count($projectIdsArray)) {
            $issueQueryParameters['project'] = $projectIdsArray;
        }
        if ($session->get('user/id')) {
            $issueQueryParameters['not_assignee'] = $userAssignedId;
        }
        $issuesUnresolvedOthers = Issue::getByParameters($issueQueryParameters, $session->get('user/id'), null, $session->get('user/id'));
        $menuSelectedCategory = 'home';

        $hasGlobalAdministrationPermission = User::hasGlobalPermission($clientId, $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasGlobalSystemAdministrationPermission = User::hasGlobalPermission($clientId, $session->get('user/id'), GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        $usersAsAssignee = User::getByClientId($clientId);
        $issueStatuses = IssueSettings::getAllIssueSettings('status', $clientId, 'array');
        $twoDimensionalData = null;
        if (count($projectIdsArray))
            $twoDimensionalData = Issue::get2DimensionalFilter($projectIdsArray[0], 'array');

        return $this->render(__DIR__ . '/../Resources/views/Index.php', get_defined_vars());
    }
}
