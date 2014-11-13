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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class HistoryController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $clientId = $session->get('client/id');
        $loggedInUserId = $session->get('user/id');

        $menuSelectedCategory = 'documentator';

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
        $clientSettings = $this->getRepository(UbirimiClient::class)->getSettings($clientId);

        $entityId = $request->get('id');
        $page = $this->getRepository(Entity::class)->getById($entityId, $loggedInUserId);

        $spaceId = $page['space_id'];
        $space = $this->getRepository(Space::class)->getById($spaceId);
        $revisions = $this->getRepository(Entity::class)->getRevisionsByPageId($entityId);

        $revisionCount = ($revisions) ? $revisions->num_rows + 1 : 1;

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / History';

        return $this->render(__DIR__ . '/../../Resources/views/page/History.php', get_defined_vars());
    }
}
