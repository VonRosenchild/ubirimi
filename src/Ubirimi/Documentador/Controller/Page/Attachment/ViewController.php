<?php
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityAttachment;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    $entityId = $_GET['id'];

    if (Util::checkUserIsLoggedIn()) {

        $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

        $page = Entity::getById($entityId, $loggedInUserId);
        if ($page)
            $spaceId = $page['space_id'];

        $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
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
        $sectionPageTitle = SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / ' . $page['name'] . ' / Attachments';
    }
    $menuSelectedCategory = 'documentator';

    if ($page) {
        $attachments = EntityAttachment::getByEntityId($entityId);
    }

    require_once __DIR__ . '/../../../Resources/views/page/attachment/View.php';