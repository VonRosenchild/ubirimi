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

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class EditController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $source_application = 'documentator';

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $entityId = $request->get('id');

        $page = $this->getRepository(Entity::class)->getById($entityId, $loggedInUserId);
        $spaceId = $page['space_id'];
        $space = $this->getRepository(Space::class)->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('general-settings/bad-link-access-denied');
        }

        $menuSelectedCategory = 'documentator';

        $session->set('current_edit_entity_id', $entityId);
        $name = $page['name'];

        $now = date('Y-m-d H:i:s');
        $activeSnapshots = $this->getRepository(Entity::class)->getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');
        $textWarningMultipleEdits = null;
        if ($activeSnapshots) {
            $textWarningMultipleEdits = 'This page is being edited by ';
            $usersUsing = array();
            for ($i = 0; $i < count($activeSnapshots); $i++) {
                if ($activeSnapshots[$i]['last_edit_offset'] <= 1) {
                    $usersUsing[] = '<a href="/documentador/user/profile/' . $activeSnapshots[$i]['user_id'] . '">' . $activeSnapshots[$i]['first_name'] . ' ' . $activeSnapshots[$i]['last_name'] . '</a>';
                }
            }

            $textWarningMultipleEdits .= implode(', ', $usersUsing);
        }

        // see if the user editing the page has a draft saved
        $lastUserSnapshot = $this->getRepository(Entity::class)->getLastSnapshot($entityId, $loggedInUserId);

        if ($request->get('edit_page')) {
            $name = $request->request->get('name');
            $content = $request->request->get('content');

            $date = Util::getServerCurrentDateTime();

            $this->getRepository(Entity::class)->addRevision($entityId, $loggedInUserId, $page['content'], $date);
            $this->getRepository(Entity::class)->updateById($entityId, $name, $content, $date);

            $this->getRepository(Entity::class)->deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

            $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'UPDATE Documentador entity ' . $name, $date);

            return new RedirectResponse('/documentador/page/view/' . $entityId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Update ' . $page['name'];

        return $this->render(__DIR__ . '/../../Resources/views/page/Edit.php', get_defined_vars());
    }
}