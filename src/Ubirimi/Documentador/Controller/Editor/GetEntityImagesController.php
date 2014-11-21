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

namespace Ubirimi\Documentador\Controller\Editor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Entity\EntityAttachment;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class GetEntityImagesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $entityId = $session->get('current_edit_entity_id');
        $attachments = $this->getRepository(EntityAttachment::class)->getByEntityId($entityId);

        $html = '';
        $index = 0;
        if ($attachments) {
            $entity = $this->getRepository(Entity::class)->getById($entityId);
            $html .= '<div style="width: 100%; height: 500px; overflow-y: scroll">';
            while ($attachment = $attachments->fetch_array(MYSQLI_ASSOC)) {

                // todo: check if the attachment is an image

                // get the last revision
                $attachmentRevisions = $this->getRepository(Entity::class)->getRevisionsByAttachmentId($attachment['id']);
                $lastRevisionNumber = $attachmentRevisions->num_rows;
                $html .= '<img data="/assets' . UbirimiContainer::get()['asset.documentador_entity_attachments'] . $entity['space_id'] . '/' . $entityId . '/' . $attachment['id'] . '/' . $lastRevisionNumber . '/' . $attachment['name'] . '" id="entity_existing_image_' . $attachment['id'] . '" style="float: left; padding-right: 10px; width: 240px" src="/assets' . UbirimiContainer::get()['asset.documentador_entity_attachments'] . $entity['space_id'] . '/' . $entityId . '/' . $attachment['id'] . '/' . $lastRevisionNumber . '/' . $attachment['name'] . '" />';
                $index++;
                if ($index > 4) {
                    $index = 0;
                    $html .= '<br />';
                }
            }
            $html .= '</div>';
        } else {
            $html .= '<div class="infoBox">There are no images for this page</div>';
        }

        return new \Symfony\Component\HttpFoundation\Response($html);
    }
}