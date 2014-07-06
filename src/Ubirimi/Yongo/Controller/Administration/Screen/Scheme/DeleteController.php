<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $screen = ScreenScheme::getMetaDataById($Id);

    ScreenScheme::deleteDataByScreenSchemeId($Id);
    ScreenScheme::deleteById($Id);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Screen Scheme ' . $screen['name'], $currentDate);