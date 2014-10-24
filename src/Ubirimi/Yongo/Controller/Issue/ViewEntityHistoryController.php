<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Repository\General\UbirimiClient;
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
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);
            $yongoSettings = $this->getRepository(UbirimiClient::class)->getYongoSettings($clientId);
        }

        $issueId = $request->request->get('issue_id');
        $userId = $request->request->get('user_id');
        $projectId = $request->request->get('project_id');
        $historyList = $this->getRepository('yongo.issue.history')->getByIssueIdAndUserId($issueId, $userId, $projectId);
        $color = null;

        $hoursPerDay = $yongoSettings['time_tracking_hours_per_day'];
        $daysPerWeek = $yongoSettings['time_tracking_days_per_week'];

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewEntityHistory.php', get_defined_vars());
    }
}