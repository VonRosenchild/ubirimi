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

namespace Ubirimi\Documentador\Controller\Page\Attachment;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $entityId = $request->get('id');

        if (Util::checkUserIsLoggedIn()) {

            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $loggedInUserId = $session->get('user/id');
            $page = $this->getRepository(Entity::class)->getById($entityId, $loggedInUserId);
            if ($page)
                $spaceId = $page['space_id'];

            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository(UbirimiClient::class)->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;

            $settingsDocumentador = $this->getRepository(UbirimiClient::class)->getDocumentadorSettings($clientId);

            $documentatorUseAnonymous = $settingsDocumentador['anonymous_use_flag'];

            $page = $this->getRepository(Entity::class)->getById($entityId, $loggedInUserId);
            if ($page) {
                $spaceId = $page['space_id'];
                $spaceHasAnonymousAccess = $this->getRepository(Space::class)->hasAnonymousAccess($spaceId);

                if (!($documentatorUseAnonymous && $spaceHasAnonymousAccess)) {
                    Util::signOutAndRedirect();
                    die();
                }
            }
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
        }
        $menuSelectedCategory = 'documentator';

        if ($page) {
            $attachments = $this->getRepository(EntityAttachment::class)->getByEntityId($entityId);
        }

        return $this->render(__DIR__ . '/../../../Resources/views/page/attachment/View.php', get_defined_vars());
    }
}