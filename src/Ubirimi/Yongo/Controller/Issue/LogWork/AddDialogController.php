<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $remainingEstimate = $request->get('remaining');

        $remainingEstimate = trim(
            str_replace(
                array('w', 'd', 'h', 'm'),
                array('w ', 'd ', 'h ', 'm'),
                $remainingEstimate
            )
        );

        $mode = 'new';
        $workLog = null;

        return $this->render(__DIR__ . '/../../../Resources/views/issue/log_work/AddDialog.php', get_defined_vars());
    }
}
