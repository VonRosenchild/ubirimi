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

namespace Ubirimi\Agile\Controller\Board;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $projects = $this->getRepository(YongoProject::class)->getByClientId($session->get('client/id'));

        $boardId = $request->get('id');
        $board = $this->getRepository(Board::class)->getById($boardId);

        if ($board['client_id'] != $session->get('client/id')) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $emptyName = false;
        $boardName = $board['name'];
        $boardDescription = $board['description'];

        if ($request->request->has('confirm_new_board')) {
            $boardName = Util::cleanRegularInputField($request->request->get('name'));
            $boardDescription = Util::cleanRegularInputField($request->request->get('description'));

            if (empty($boardName)) {
                $emptyName = true;
            }

            if (!$emptyName) {

                $date = Util::getServerCurrentDateTime();

                $this->getRepository(Board::class)->updateMetadata($session->get('client/id'), $boardId, $boardName, $boardDescription, $date);

                $this->getLogger()->addInfo('UPDATE Cheetah Agile Board ' . $boardName, $this->getLoggerContext());

                return new RedirectResponse('/agile/boards');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Update Board';

        return $this->render(__DIR__ . '/../../Resources/views/board/Edit.php', get_defined_vars());
    }
}
