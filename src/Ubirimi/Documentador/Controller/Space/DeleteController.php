<?php
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $spaceId = $_POST['id'];

    $space = Space::getById($spaceId);
    Space::deleteById($spaceId);

    $date = Util::getServerCurrentDateTime();

    $this->getRepository('ubirimi.general.log')->add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'DELETE Documentador space ' . $space['name'], $date);