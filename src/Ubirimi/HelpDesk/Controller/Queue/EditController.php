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

namespace Ubirimi\HelpDesk\Controller\Queue;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $queueId = $request->get('id');
        $queue = $this->getRepository(Queue::class)->getById($queueId);
        $projectId = $queue['project_id'];

        $emptyName = false;
        $queueExists = false;

        if ($request->request->has('edit_queue')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $description = Util::cleanRegularInputField($request->request->get('description'));
            $description = Util::cleanRegularInputField($request->request->get('definition'));

            if (empty($name)) {
                $emptyName = true;
            }

            // check for duplication
            $queue = $this->getRepository(Queue::class)->getByName($queueId, mb_strtolower($name), $projectId);
            if ($queue) {
                $queueExists = true;
            }

            if (!$queueExists && !$emptyName) {
                $currentDate = Util::getServerCurrentDateTime();

                $this->getRepository(Queue::class)->updateById($queueId, $name, $description, $description, $currentDate);

                return new RedirectResponse('/helpdesk/queues/' . $projectId . '/' . $queueId);
            }
        }

        $menuSelectedCategory = 'issue';
        $sectionPageTitle = $session->get('client/settings/title_name')
            . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME
            . ' / Update Queue';

        return $this->render(__DIR__ . '/../../Resources/views/queue/Edit.php', get_defined_vars());
    }
}
