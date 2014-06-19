<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityType;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $name = $_POST['name'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $parentId = $_POST['parent_id'];
    $spaceId = $_POST['space_id'];
    if ($type == 'file_list')
        $pageType = EntityType::ENTITY_FILE_LIST;
    else
        $pageType = EntityType::ENTITY_BLANK_PAGE;

    if ($parentId == -1) {
        // set the parent to the home page of the space if it exists
        $space = Space::getById($spaceId);
        $homeEntityId = $space['home_entity_id'];
        if ($homeEntityId) {
            $parentId = $homeEntityId;
        } else {
            $parentId = null;
        }
    }

    $page = new Entity($pageType, $spaceId, $loggedInUserId, $parentId, $name, $description);
    $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    $pageId = $page->save($currentDate);

    // if the page is a file list create the folders
    $baseFilePath = Util::getAssetsFolder(SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'filelists');
    if ($pageType == EntityType::ENTITY_FILE_LIST) {
        mkdir($baseFilePath . $pageId);
    }

    echo $pageId;