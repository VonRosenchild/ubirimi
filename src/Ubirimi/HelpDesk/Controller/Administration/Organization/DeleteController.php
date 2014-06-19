<?php
    use Ubirimi\Repository\HelpDesk\Organization;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $organizationId = $_POST['id'];
    Organization::deleteById($organizationId);
