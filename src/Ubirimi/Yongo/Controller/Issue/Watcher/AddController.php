<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Watcher;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $userIds = $request->request->get('id');
        $issueId = $request->request->get('issue_id');

        $currentDate = Util::getServerCurrentDateTime();
        if ($userIds) {
            for ($i = 0; $i < count($userIds); $i++) {
                $this->getRepository(Watcher::class)->add($issueId, $userIds[$i], $currentDate);
            }

            // update the date_updated field
            $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);
        }
    }
}