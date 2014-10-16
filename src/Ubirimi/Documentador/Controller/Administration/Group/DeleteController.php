<?php

    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_POST['id'];

    $this->getRepository('ubirimi.user.group')->deleteByIdForYongo($groupId);