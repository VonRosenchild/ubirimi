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

        $action = $request->request->get('action');
        $issueId = $request->request->get('id');

        $currentDate = Util::getServerCurrentDateTime();

        if ($action == 'add') {
            $this->getRepository('yongo.issue.watcher')->add($issueId, $loggedInUserId, $currentDate);
        } else if ($action == 'remove') {
            $this->getRepository('yongo.issue.watcher')->deleteByUserIdAndIssueId($issueId, $loggedInUserId);
        }

        // update the date_updated field
        $this->getRepository('yongo.issue.issue')->updateById($issueId, array('date_updated' => $currentDate), $currentDate);
    }
}