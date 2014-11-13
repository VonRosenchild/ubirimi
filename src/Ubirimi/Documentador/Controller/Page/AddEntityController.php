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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityType;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class AddEntityController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $loggedInUserId = $session->get('user/id');

        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $type = $request->request->get('type');
        $parentId = $request->request->get('parent_id');
        $spaceId = $request->request->get('space_id');

        if ($type == 'file_list') {
            $pageType = EntityType::ENTITY_FILE_LIST;
        } else {
            $pageType = EntityType::ENTITY_BLANK_PAGE;
        }

        if ($parentId == -1) {
            // set the parent to the home page of the space if it exists
            $space = $this->getRepository(Space::class)->getById($spaceId);
            $homeEntityId = $space['home_entity_id'];
            if ($homeEntityId) {
                $parentId = $homeEntityId;
            } else {
                $parentId = null;
            }
        }

        $page = new Entity($pageType, $spaceId, $loggedInUserId, $parentId, $name, $description);
        $currentDate = Util::getServerCurrentDateTime();
        $pageId = $page->save($currentDate);

        // if the page is a file list create the folders
        $baseFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
        if ($pageType == EntityType::ENTITY_FILE_LIST) {
            mkdir($baseFilePath . $pageId);
        }

        return new Response($pageId);
    }
}