<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\History;

class ViewEntityHistoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        if (Util::checkUserIsLoggedIn()) {
            $yongoSettings = $session->get('yongo/settings');
            $clientId = $session->get('client/id');
            $clientSettings = $session->get('client/settings');
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository('ubirimi.general.client')->getSettings($clientId);
            $yongoSettings = $this->getRepository('ubirimi.general.client')->getYongoSettings($clientId);
        }

        $issueId = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $projectId = isset($_POST['project_id']) ? $_POST['project_id'] : null;
        $historyList = History::getByIssueIdAndUserId($issueId, $userId, $projectId);
        $color = null;

        $hoursPerDay = $yongoSettings['time_tracking_hours_per_day'];
        $daysPerWeek = $yongoSettings['time_tracking_days_per_week'];

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewEntityHistory.php', get_defined_vars());
    }
}