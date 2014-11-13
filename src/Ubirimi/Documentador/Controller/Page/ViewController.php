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
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\Documentador\Repository\Entity\EntityComment;
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

            $page = $this->getRepository(Entity::class)->getById($entityId, $session->get('user/id'));
            if ($page) {
                $spaceId = $page['space_id'];
            }

            $sectionPageTitle = $session->get('client/settings/title_name')
                . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME
                . ' / ' . $page['name'];
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
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'];
        }

        $menuSelectedCategory = 'documentator';

        if ($page) {
            $parentEntityId = $page['parent_entity_id'];
            $parentPage = null;
            if ($parentEntityId) {
                $parentPage = $this->getRepository(Entity::class)->getById($parentEntityId);
            }

            $revisionId = $request->attributes->has('rev_id') ? str_replace('/', '', $request->get('rev_id')) : null;

            if ($revisionId) {
                $revision = $this->getRepository(Entity::class)->getRevisionsByPageIdAndRevisionId($entityId, $revisionId);
            }

            $space = $this->getRepository(Space::class)->getById($spaceId);

            if ($space['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }

            $pagesInSpace = $this->getRepository(Entity::class)->getAllBySpaceId($spaceId);
            $treeStructure = $this->getRepository(Space::class)->generateTreeStructure($pagesInSpace, $entityId);

            $comments = $this->getRepository(EntityComment::class)->getComments($entityId, 'array');
            $lastRevision = $this->getRepository(Entity::class)->getLastRevisionByPageId($entityId);
            $childPages = $this->getRepository(Entity::class)->getChildren($entityId);
            $pageFiles = $this->getRepository(Entity::class)->getFilesByEntityId($entityId);
            $attachments = $this->getRepository(EntityAttachment::class)->getByEntityId($entityId);
        }

        return $this->render(__DIR__ . '/../../Resources/views/page/View.php', get_defined_vars());
    }
}
