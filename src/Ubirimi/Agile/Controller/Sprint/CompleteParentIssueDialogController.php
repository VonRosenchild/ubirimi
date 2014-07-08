<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\UbirimiController;

class CompleteParentIssueDialogController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $data = $request->request->get('data');
        $dataValues = json_decode($data, true);
        $textSelected = 'checked="checked"';

        return $this->render(__DIR__ . '/../../Resources/views/sprint/CompleteParentIssueDialog.php', get_defined_vars());
    }
}
