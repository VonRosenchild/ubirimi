<?php

namespace Ubirimi\Yongo\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\Client;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;

class ActivityStreamController extends UbirimiController
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
        $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Activity Stream';

        $projectsMenu = Client::getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $projectIds = Util::array_column($projectsMenu, 'id');

        $historyList = Util::getProjectHistory($projectIds, 0);
        $historyData = array();
        $userData = array();
        while ($history = $historyList->fetch_array(MYSQLI_ASSOC)) {
            $historyData[substr($history['date_created'], 0, 10)][$history['user_id']][$history['date_created']][] = $history;
            $userData[$history['user_id']] = array('picture' => $history['avatar_picture'],
                                                   'first_name' => $history['first_name'],
                                                   'last_name' => $history['last_name']);
        }

        return $this->render(__DIR__ . '/../Resources/views/ActivityStream.php', get_defined_vars());
    }
}
