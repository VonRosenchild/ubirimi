<?php

namespace Ubirimi\Yongo\Controller\Issue\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class AddDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        return $this->render(__DIR__ . '/../../../Resources/views/issue/attachment/AddDialog.php', get_defined_vars());
    }
}
