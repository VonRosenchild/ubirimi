<?php

namespace Ubirimi\Yongo\Controller\Issue\LogWork;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\WorkLog;

class EditDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $workLogId = $request->get('work_log_id');
        $remainingEstimate = $request->get('remaining');
        if (-1 != $remainingEstimate) {
            $remainingEstimate = trim(str_replace(array('w', 'd', 'h', 'm'), array('w ', 'd ', 'h ', 'm'), $remainingEstimate));
        }
        $workLog = $this->getRepository(WorkLog::class)->getById($workLogId);

        $mode = 'edit';

        return $this->render(__DIR__ . '/../../../Resources/views/issue/log_work/EditDialog.php', get_defined_vars());
    }
}