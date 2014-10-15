<?php

namespace Ubirimi\Documentador\Controller\Page;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



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

            $page = Entity::getById($entityId, $session->get('user/id'));
            if ($page)
                $spaceId = $page['space_id'];

            $sectionPageTitle = $session->get('client/settings/title_name')
                . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME
                . ' / ' . $page['name'];
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
            $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'];
        }

        $menuSelectedCategory = 'documentator';

        if ($page) {
            $parentEntityId = $page['parent_entity_id'];
            $parentPage = null;
            if ($parentEntityId)
                $parentPage = Entity::getById($parentEntityId);

            $revisionId = $request->attributes->has('rev_id') ? str_replace('/', '', $request->get('rev_id')) : null;

            if ($revisionId)
                $revision = Entity::getRevisionsByPageIdAndRevisionId($entityId, $revisionId);

            $space = Space::getById($spaceId);

            if ($space['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }

            $comments = EntityComment::getComments($entityId, 'array');
            $lastRevision = Entity::getLastRevisionByPageId($entityId);
            $childPages = Entity::getChildren($entityId);
            $pageFiles = Entity::getFilesByEntityId($entityId);
            $attachments = EntityAttachment::getByEntityId($entityId);
        }

        return $this->render(__DIR__ . '/../../Resources/views/page/View.php', get_defined_vars());
    }
}
