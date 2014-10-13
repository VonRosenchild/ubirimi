<?php

namespace Ubirimi\Yongo\Controller\Project;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\Project;
use Ubirimi\Repository\Client;

class ViewActivityStreamController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $projectId = $request->request->get('id');
        $loggedInUserId = $session->get('user/id');
        $clientId = $session->get('client/id');

        $client = $this->getRepository('ubirimi.general.client')->getById($clientId);
        $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);

        if (Util::checkUserIsLoggedIn()) {
            $hasBrowsingPermission = $this->getRepository('yongo.project.project')->userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS, $loggedInUserId);
        } else {
            $loggedInUserId = null;
            $httpHOST = Util::getHttpHost();
            $hasBrowsingPermission = $this->getRepository('yongo.project.project')->userHasPermission(array($projectId), Permission::PERM_BROWSE_PROJECTS);
        }

        if ($hasBrowsingPermission) {
            $helpDeskFlag = 0;
            if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_HELP_DESK) {
                $helpDeskFlag = 1;
            }

            $endDate = Util::getServerCurrentDateTime();
            $startDate = date_sub(new \DateTime($endDate, new \DateTimeZone($clientSettings['timezone'])), date_interval_create_from_date_string('2 days'));

            $historyList = null;
            do {
                $historyList = Util::getProjectHistory(array($projectId), $helpDeskFlag, null, date_format($startDate, 'Y-m-d'), $endDate);
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
        }

        $index = 0;

        return $this->render(__DIR__ . '/../../Resources/views/project/ViewActivityStream.php', get_defined_vars());
    }
}
