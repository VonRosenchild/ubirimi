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
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Yongo\Repository\Permission\Permission;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $menuSelectedCategory = 'agile';
        $projects = $this->getRepository(UbirimiClient::class)->getProjectsByPermission(
            $session->get('client/id'),
            $session->get('user/id'),
            Permission::PERM_BROWSE_PROJECTS
        );

        $noProjectSelected = false;
        $emptyName = false;

        if ($request->request->has('confirm_new_board')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $projectsInBoard = $request->request->get('project');

            if (!$projectsInBoard) {
                $noProjectSelected = true;
            }
            if (empty($name)) {
                $emptyName = true;
            }
            if (!$emptyName && !$noProjectSelected) {
                $definitionData = 'project=' . implode('|', $projectsInBoard);
                $date = Util::getServerCurrentDateTime();

                $filterId = $this->getRepository(IssueFilter::class)->save(
                    $session->get('user/id'),
                    'Filter for ' . $name,
                    'Filter created automatically for agile board ' . $name,
                    $definitionData,
                    $date
                );

                $board = new Board($session->get('client/id'), $filterId, $name, $description, $projectsInBoard);
                $currentDate = Util::getServerCurrentDateTime();
                $boardId = $board->save($session->get('user/id'), $currentDate);
                $board->addDefaultColumnData($session->get('client/id'), $boardId);

                $this->getRepository(UbirimiLog::class)->add(
                    $session->get('client/id'),
                    SystemProduct::SYS_PRODUCT_AGILE,
                    $session->get('user/id'),
                    'ADD Cheetah Agile Board ' . $name,
                    $date
                );

                return new RedirectResponse('/agile/boards');
            }
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Create Board';

        return $this->render(__DIR__ . '/../../Resources/views/board/Add.php', get_defined_vars());
    }
}
