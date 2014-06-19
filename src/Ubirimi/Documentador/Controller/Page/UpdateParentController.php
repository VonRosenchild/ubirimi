<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $entityId = $_POST['entity_id'];
    $parentId = $_POST['parent_id'];

    Entity::updateParent($entityId, $parentId);