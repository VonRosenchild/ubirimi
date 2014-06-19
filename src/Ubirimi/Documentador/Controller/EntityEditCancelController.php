<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $spaceId = $_GET['space_id'];
    $entityId = $_GET['entity_id'];

    Entity::deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

    header('Location: /documentador/pages/' . $spaceId);