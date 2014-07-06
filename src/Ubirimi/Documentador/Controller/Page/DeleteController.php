<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];
    $spaceId = $_POST['space_id'];

    $entity = Entity::getById($pageId);

    Entity::moveToTrash($pageId);

    $date = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'MOVE TO TRASH Documentador entity ' . $entity['name'], $date);