<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $revisionId = $_POST['id'];

    Entity::deleteRevisionById($revisionId);