<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

class EditDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $issueId = $request->get('id');
        $issueData = UbirimiContainer::get()['repository']->get('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $session->get('user/id'), null, $session->get('user/id'));
        $issueTypeId = $issueData['issue_type_id'];

        return $this->render(__DIR__ . '/../../Resources/views/issue/EditDialog.php', get_defined_vars());
    }
}