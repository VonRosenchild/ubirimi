<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ToggleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $action = $_POST['action'];
        $issueId = $_POST['id'];

        $currentDate = Util::getServerCurrentDateTime();

        if ($action == 'add') {
            Watcher::addWatcher($issueId, $loggedInUserId, $currentDate);
        } else if ($action == 'remove') {
            Watcher::deleteByUserIdAndIssueId($issueId, $loggedInUserId);
        }

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);
    }
}