<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityType;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    $source_application = 'documentator';

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $spaceId = $_GET['space_id'];
    $space = Space::getById($spaceId);

    if ($space['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $parentEntityId = isset($_GET['entity_id']) ? $_GET['entity_id'] : null;
    if ($parentEntityId)
        $parentEntityId = str_replace("/", "", $parentEntityId);

    if (empty($parentEntityId)) {
        // set the parent to the home page of the space if it exists
        $space = Space::getById($spaceId);
        $homeEntityId = $space['home_entity_id'];
        if ($homeEntityId) {
            $parentEntityId = $homeEntityId;
        } else {
            $parentEntityId = null;
        }
    }

    $menuSelectedCategory = 'documentator';

    if (isset($_POST['add_page'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $content = $_POST['content'];

        $page = new Entity(EntityType::ENTITY_BLANK_PAGE, $spaceId, $loggedInUserId, $parentEntityId, $name, $content);
        $currentDate = Util::getServerCurrentDateTime();
        $pageId = $page->save($currentDate);

        $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador Entity ' . $name, $currentDate);

        header('Location: /documentador/page/view/' . $pageId);
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_DOCUMENTADOR_NAME. ' / Create Page';

    require_once __DIR__ . '/../../Resources/views/page/Add.php';