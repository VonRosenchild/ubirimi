<?php

namespace Ubirimi\Yongo\Controller\Issue\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class SaveController extends UbirimiController
{

    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->request->get('issue_id');
        $attachIdsToBeKept = $request->request->get('attach_ids');
        $comment = Util::cleanRegularInputField($request->request->get('comment'));

        if (!is_array($attachIdsToBeKept)) {
            $attachIdsToBeKept = array();
        }

        Util::manageModalAttachments($issueId, $session->get('user/id'), $attachIdsToBeKept);

        if (!empty($comment)) {
            $currentDate = Util::getServerCurrentDateTime();
            $this->getRepository('yongo.issue.comment')->add($issueId, $session->get('user/id'), $comment, $currentDate);
        }
    }
}