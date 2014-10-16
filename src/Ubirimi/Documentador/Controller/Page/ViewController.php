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

            $page = $this->getRepository('documentador.entity.entity')->getById($entityId, $session->get('user/id'));
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

            $page = $this->getRepository('documentador.entity.entity')->getById($entityId, $loggedInUserId);
            if ($page) {
                $spaceId = $page['space_id'];
                $spaceHasAnonymousAccess = $this->getRepository('documentador.space.space')->hasAnonymousAccess($spaceId);

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
                $parentPage = $this->getRepository('documentador.entity.entity')->getById($parentEntityId);

            $revisionId = $request->attributes->has('rev_id') ? str_replace('/', '', $request->get('rev_id')) : null;

            if ($revisionId)
                $revision = $this->getRepository('documentador.entity.entity')->getRevisionsByPageIdAndRevisionId($entityId, $revisionId);

            $space = $this->getRepository('documentador.space.space')->getById($spaceId);

            if ($space['client_id'] != $session->get('client/id')) {
                return new RedirectResponse('/general-settings/bad-link-access-denied');
            }

            $comments = $this->getRepository('documentador.entity.comment')->getComments($entityId, 'array');
            $lastRevision = $this->getRepository('documentador.entity.entity')->getLastRevisionByPageId($entityId);
            $childPages = $this->getRepository('documentador.entity.entity')->getChildren($entityId);
            $pageFiles = $this->getRepository('documentador.entity.entity')->getFilesByEntityId($entityId);
            $attachments = $this->getRepository('documentador.entity.attachment')->getByEntityId($entityId);
        }

        return $this->render(__DIR__ . '/../../Resources/views/page/View.php', get_defined_vars());
    }
}
