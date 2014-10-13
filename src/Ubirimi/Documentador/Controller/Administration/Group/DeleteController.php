<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_POST['id'];

    $this->getRepository('ubirimi.user.group')->deleteByIdForYongo($groupId);