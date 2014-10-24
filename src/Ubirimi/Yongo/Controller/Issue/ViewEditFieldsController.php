<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class ViewEditFieldsController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $issueId = $request->request->get('issue_id');
        $issueTypeId = $request->request->get('issue_type_id');
        $issueData = $this->getRepository(Issue::class)->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewEditDialog.php', get_defined_vars());
    }
}
