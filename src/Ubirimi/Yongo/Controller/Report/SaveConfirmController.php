<?php

namespace Ubirimi\Yongo\Controller\Report;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;

class SaveConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $filterId = $request->get('filter_id');

        $filter = null;
        if ($filterId != -1) {
            $filter = $this->getRepository(IssueFilter::class)->getById($filterId);
        }

        return $this->render(__DIR__ . '/../../Resources/views/filter/SaveConfirm.php', get_defined_vars());
    }
}
