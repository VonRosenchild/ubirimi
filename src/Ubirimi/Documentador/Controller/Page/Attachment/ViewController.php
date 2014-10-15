<?php

namespace Ubirimi\Documentador\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\SystemProduct;
use Ubirimi\UbirimiController;
use Ubirimi\Util;

class ViewController extends UbirimiController
{
    public function indexAction(Request $request, SessionInterface $session)
    {
        $entityId = $_GET['id'];

        if (Util::checkUserIsLoggedIn()) {

            $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);
            $loggedInUserId = $session->get('user/id');
            $page = Entity::getById($entityId, $loggedInUserId);
            if ($page)
                $spaceId = $page['space_id'];

            $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
        } else {
            $httpHOST = Util::getHttpHost();
            $clientId = $this->getRepository('ubirimi.general.client')->getByBaseURL($httpHOST, 'array', 'id');
            $loggedInUserId = null;

            $settingsDocumentator = $this->getRepository('ubirimi.general.client')->getDocumentatorSettings($clientId);

            $documentatorUseAnonymous = $settingsDocumentator['anonymous_use_flag'];

            $page = Entity::getById($entityId, $loggedInUserId);
            if ($page) {
                $spaceId = $page['space_id'];
                $spaceHasAnonymousAccess = Space::hasAnonymousAccess($spaceId);

                if (!($documentatorUseAnonymous && $spaceHasAnonymousAccess)) {
                    Util::signOutAndRedirect();
                    die();
                }
            }
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
        }
        $menuSelectedCategory = 'documentator';

        if ($page) {
            $attachments = EntityAttachment::getByEntityId($entityId);
        }

        require_once __DIR__ . '/../../../Resources/views/page/attachment/View.php';
    }
}