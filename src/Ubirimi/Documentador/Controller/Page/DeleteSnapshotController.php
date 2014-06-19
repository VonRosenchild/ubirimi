<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $snapShotId = $_POST['id'];

    Entity::deleteSnapshotById($snapShotId);