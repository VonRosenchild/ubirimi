<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewEditFieldsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->request->get('issue_id');
        $issueTypeId = $request->request->get('issue_type_id');
        $issueData = $this->getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        require_once __DIR__ . '/../../Resources/views/issue/ViewEditDialog.php';
    }
}
