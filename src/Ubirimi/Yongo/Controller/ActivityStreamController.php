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

        $client = Client::getById($clientId);
        $projectsMenu = Client::getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS,
            'array'
        );

        $menuSelectedCategory = 'home';

        $projectIds = Util::array_column($projectsMenu, 'id');

        $endDate = Util::getServerCurrentDateTime();
        $startDate = date_sub(new \DateTime($endDate, new \DateTimeZone($clientSettings['timezone'])), date_interval_create_from_date_string('2 days'));

        $historyList = null;
        do {
            $historyList = Util::getProjectHistory($projectIds, 0, null, date_format($startDate, 'Y-m-d'), $endDate);
            if (null == $historyList && date_format($startDate, 'Y-m-d H:i:s') == $client['date_created']) {
                break;
            }
            $startDate = date_sub($startDate, date_interval_create_from_date_string('2 days'));
            $startDate->setTime(0, 0, 0);
            if (date_format($startDate, 'Y-m-d') < $client['date_created']) {
                $startDate = new \DateTime($client['date_created'], new \DateTimeZone($clientSettings['timezone']));
                break;
            }
        } while ($historyList == null);

        $historyData = array();
        $userData = array();

        while ($historyList && $history = $historyList->fetch_array(MYSQLI_ASSOC)) {
            $historyData[substr($history['date_created'], 0, 10)][$history['user_id']][$history['date_created']][] = $history;
            $userData[$history['user_id']] = array('picture' => $history['avatar_picture'],
                                                   'first_name' => $history['first_name'],
                                                   'last_name' => $history['last_name']);
        }

        $index = 0;
        return $this->render(__DIR__ . '/../Resources/views/ActivityStream.php', get_defined_vars());
    }
}
