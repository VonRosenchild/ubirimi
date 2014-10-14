<?php




    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $entityId = $_POST['id'];

    $entity = Entity::getById($entityId);

    EntityComment::deleteCommentsByEntityId($entityId);
    Entity::removeAsFavouriteForUsers($entityId);
    Entity::deleteRevisionsByEntityId($entityId);
    Entity::deleteFilesByEntityId($entityId);

    EntityAttachment::deleteByEntityId($entityId, $entity['space_id']);
    Entity::deleteById($entityId);

    $date = Util::getServerCurrentDateTime();
    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador entity ' . $entity['name'], $date);