<?php

namespace Ubirimi\Yongo\Controller\Issue;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class ViewIssueSearchMenuController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $issueId = $request->request->get('id');

        return $this->render(__DIR__ . '/../../Resources/views/issue/ViewIssueSearchMenu.php', get_defined_vars());
    }
}