<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class AssignController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $currentDate = Util::getServerCurrentDateTime();

        $issueId = $_POST['issue_id'];
        $userAssignedId = $_POST['user_assigned_id'];
        $comment = Util::cleanRegularInputField($_POST['comment']);

        Issue::updateAssignee($session->get('client/id'), $issueId, $session->get('user/id'), $userAssignedId, $comment);

        // update the date_updated field
        Issue::updateById($issueId, array('date_updated' => $currentDate), $currentDate);

        return new Response('');
    }
}