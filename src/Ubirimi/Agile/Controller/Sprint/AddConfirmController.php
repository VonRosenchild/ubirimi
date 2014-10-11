<?php

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->get('board_id');
        $lastSprint = Sprint::getLast($boardId);
        $suggestedName = '';

        if ($lastSprint) {
            $name = $lastSprint['name'];
            $nameComponents = explode(' ', $name);

            if (is_numeric($nameComponents[count($nameComponents) - 1])) {
                $value = $nameComponents[count($nameComponents) - 1];
                $value++;
                array_pop($nameComponents);
                if (count($nameComponents) == 1)
                    $suggestedName = $nameComponents[0] . ' ' . $value;
                else
                    $suggestedName = implode(' ', $nameComponents) . ' ' . $value;
            }
        } else {
            $suggestedName = 'Sprint 1';
        }

        return $this->render(__DIR__ . '/../../Resources/views/sprint/AddConfirm.php', get_defined_vars());
    }
}
