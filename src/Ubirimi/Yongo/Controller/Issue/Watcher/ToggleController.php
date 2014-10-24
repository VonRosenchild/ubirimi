<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Watcher;

class ToggleController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = UbirimiContainer::get()['session']->get('user/id');

        $action = $request->request->get('action');
        $issueId = $request->request->get('id');

        $currentDate = Util::getServerCurrentDateTime();

        if ($action == 'add') {
            $this->getRepository(Watcher::class)->add($issueId, $loggedInUserId, $currentDate);
        } else if ($action == 'remove') {
            $this->getRepository(Watcher::class)->deleteByUserIdAndIssueId($issueId, $loggedInUserId);
        }

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);
    }
}