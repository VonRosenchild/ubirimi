<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $snapShotId = $_POST['id'];

    Entity::deleteSnapshotById($snapShotId);