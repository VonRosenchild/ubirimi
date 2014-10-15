<?php

namespace Ubirimi\Documentador\Controller\Editor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class GetEntityImagesController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        Util::checkUserIsLoggedInAndRedirect();

        $entityId = $session->get('current_edit_entity_id');
        $attachments = EntityAttachment::getByEntityId($entityId);

        $index = 0;
        if ($attachments) {
            $entity = Entity::getById($entityId);
            echo '<div style="width: 100%; height: 500px; overflow-y: scroll">';
            while ($attachment = $attachments->fetch_array(MYSQLI_ASSOC)) {

                // todo: check if the attachment is an image

                // get the last revision
                $attachmentRevisions = Entity::getRevisionsByAttachmentId($attachment['id']);
                $lastRevisionNumber = $attachmentRevisions->num_rows;
                echo '<img data="/assets' . UbirimiContainer::get()['asset.documentador_entity_attachments'] . $entity['space_id'] . '/' . $entityId . '/' . $attachment['id'] . '/' . $lastRevisionNumber . '/' . $attachment['name'] . '" id="entity_existing_image_' . $attachment['id'] . '" style="float: left; padding-right: 10px; width: 240px" src="/assets' . UbirimiContainer::get()['asset.documentador_entity_attachments'] . $entity['space_id'] . '/' . $entityId . '/' . $attachment['id'] . '/' . $lastRevisionNumber . '/' . $attachment['name'] . '" />';
                $index++;
                if ($index > 4) {
                    $index = 0;
                    echo '<br />';
                }
            }
            echo '</div>';
        } else {
            echo '<div class="infoBox">There are no images for this page</div>';
        }
    }
}