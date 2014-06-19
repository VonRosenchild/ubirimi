<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $revisionId = $_POST['id'];

    Entity::deleteRevisionById($revisionId);