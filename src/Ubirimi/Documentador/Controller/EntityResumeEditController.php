<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    $source_application = 'documentator';

    Util::checkUserIsLoggedInAndRedirect();

    $snapshotId = $_GET['id'];
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR);

    $snapshot = Entity::getSnapshotById($snapshotId);
    $entityId = $snapshot['documentator_entity_id'];
    Entity::updateContent($entityId, $snapshot['content']);
    Entity::deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId);

    header('Location: /documentador/page/edit/' . $entityId);