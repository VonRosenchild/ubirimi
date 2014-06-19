<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $entityId = $_POST['id'];
    $entityLastSnapshotId = $_POST['entity_last_snapshot_id'];
    $newEntityContent = $_POST['content'];
    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    $entity = Entity::getById($entityId);
    $oldEntityContent = $entity['content'];

    $newEntityContent =  preg_replace("/[[:cntrl:]]/", "", $newEntityContent); ;
    $oldEntityContent =  preg_replace("/[[:cntrl:]]/", "", $oldEntityContent); ;

    if (md5($oldEntityContent) != md5($newEntityContent)) {
        Entity::deleteAllSnapshotsByEntityIdAndUserId($entityId, $loggedInUserId, $entityLastSnapshotId);
        Entity::addSnapshot($entityId, $newEntityContent, $loggedInUserId, $date);

        $now = date('Y-m-d H:i:s');
        $activeSnapshots = Entity::getOtherActiveSnapshots($entityId, $loggedInUserId, $now, 'array');

        echo json_encode($activeSnapshots);
    }