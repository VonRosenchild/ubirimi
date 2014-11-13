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
use Ubirimi\Documentador\Repository\Entity\EntityType;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiLog;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $source_application = 'documentator';

        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $spaceId = $request->get('space_id');
        $space = $this->getRepository(Space::class)->getById($spaceId);

        if ($space['client_id'] != $clientId) {
            return new RedirectResponse('/general-settings/bad-link-access-denied');
        }

        $parentEntityId =  $request->get('entity_id');
        if ($parentEntityId) {
            $parentEntityId = str_replace("/", "", $parentEntityId);
        }

        if (empty($parentEntityId)) {
            // set the parent to the home page of the space if it exists
            $space = $this->getRepository(Space::class)->getById($spaceId);
            $homeEntityId = $space['home_entity_id'];
            if ($homeEntityId) {
                $parentEntityId = $homeEntityId;
            } else {
                $parentEntityId = null;
            }
        }

        $menuSelectedCategory = 'documentator';

        if ($request->request->has('add_page')) {
            $name = Util::cleanRegularInputField($request->request->get('name'));
            $content = $request->request->get('content');

            $page = new Entity(EntityType::ENTITY_BLANK_PAGE, $spaceId, $loggedInUserId, $parentEntityId, $name, $content);
            $currentDate = Util::getServerCurrentDateTime();
            $pageId = $page->save($currentDate);

            $this->getRepository(UbirimiLog::class)->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador Entity ' . $name, $currentDate);

            return new RedirectResponse('/documentador/page/view/' . $pageId);
        }

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Create Page';

        return $this->render(__DIR__ . '/../../Resources/views/page/Add.php', get_defined_vars());
    }
}