<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userIds = $_POST['id'];
        $issueId = $_POST['issue_id'];

        $currentDate = Util::getServerCurrentDateTime();
        if ($userIds) {
            for ($i = 0; $i < count($userIds); $i++) {
                Watcher::addWatcher($issueId, $userIds[$i], $currentDate);
            }

            // update the date_updated field
            Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);
        }
    }
}