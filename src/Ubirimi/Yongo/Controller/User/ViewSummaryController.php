<?php

namespace Ubirimi\Yongo\Controller\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Repository\Group\Group;
use Ubirimi\Repository\User\User;
use Ubirimi\Yongo\Repository\Issue\Statistic;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ViewSummaryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $projectId = $session->get('selected_project_id');
        $userId = $request->get('id');
        $loggedInUserId = $session->get('client/id');
        $user = User::getById($userId);

        if ($user['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $groups = Group::getByUserIdAndProductId($userId, SystemProduct::SYS_PRODUCT_YONGO);
        $stats = Statistic::getUnresolvedIssuesByProjectForUser($userId);

        $hasAdministrationGlobalPermission = User::hasGlobalPermission($session->get('client/id'), $userId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS);
        $hasSystemAdministrationGlobalPermission = User::hasGlobalPermission($session->get('client/id'), $userId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);

        $projectsForBrowsing = Client::getProjectsByPermission($session->get('client/id'), $session->get('user/id'), Permission::PERM_BROWSE_PROJECTS, 'array');
        $projectIds = array();

        if ($projectsForBrowsing) {
            $projectIds = Util::array_column($projectsForBrowsing, 'id');
        }
        $menuSelectedCategory = 'user';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

        $hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
        $daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / User: ' . $user['first_name'] . ' ' . $user['last_name'] . ' / Profile';

        return $this->render(__DIR__ . '/../../Resources/views/user/ViewSummary.php', get_defined_vars());
    }
}
