<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

namespace Ubirimi\Agile\Controller\Sprint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddConfirmController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $boardId = $request->get('board_id');
        $lastSprint = $this->getRepository(Sprint::class)->getLast($boardId);
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
