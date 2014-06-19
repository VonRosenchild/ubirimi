<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityAttachment;
    use Ubirimi\Repository\Documentador\EntityComment;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    $entityId = $_GET['id'];
    if (Util::checkUserIsLoggedIn()) {
        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $page = Entity::getById($entityId, $loggedInUserId);
        if ($page)
            $spaceId = $page['space_id'];
        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'];
    } else {
        $httpHOST = Util::getHttpHost();
        $clientId = Client::getByBaseURL($httpHOST, 'array', 'id');
        $loggedInUserId = null;

        $settingsDocumentator = Client::getDocumentatorSettings($clientId);

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

        $revisionId = isset($_GET['rev_id']) ? str_replace('/', '', $_GET['rev_id']) : null;

        if ($revisionId)
            $revision = Entity::getRevisionsByPageIdAndRevisionId($entityId, $revisionId);

        $space = Space::getById($spaceId);

        if ($space['client_id'] != $clientId) {
            header('Location: /general-settings/bad-link-access-denied');
            die();
        }

        $comments = EntityComment::getComments($entityId, 'array');
        $lastRevision = Entity::getLastRevisionByPageId($entityId);
        $childPages = Entity::getChildren($entityId);
        $pageFiles = Entity::getFilesByEntityId($entityId);
        $attachments = EntityAttachment::getByEntityId($entityId);
    }

    require_once __DIR__ . '/../../Resources/views/page/View.php';