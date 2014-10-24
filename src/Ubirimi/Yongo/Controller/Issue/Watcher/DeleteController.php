<?php

namespace Ubirimi\Yongo\Controller\Issue\Watcher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\Watcher;

class DeleteController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $currentDate = Util::getServerCurrentDateTime();

        $userId = $request->request->get('id');
        $issueId = $request->request->get('issue_id');

        $this->getRepository(Watcher::class)->deleteByUserIdAndIssueId($issueId, $userId);

        // update the date_updated field
        $this->getRepository(Issue::class)->updateById($issueId, array('date_updated' => $currentDate), $currentDate);
    }
}
