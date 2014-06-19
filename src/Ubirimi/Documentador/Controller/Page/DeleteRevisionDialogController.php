<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_GET['entity_id'];
    $revisionNR = $_GET['rev_nr'];
    $page = Entity::getById($pageId);

    echo 'Are you sure you want to remove revision ' . $revisionNR . ' for ' . $page['name'];