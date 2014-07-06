<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueTypeSchemeId = $_POST['id'];

    $issueTypeScheme = IssueTypeScheme::getMetaDataById($issueTypeSchemeId);
    IssueTypeScheme::deleteById($issueTypeSchemeId);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Issue Type Scheme ' . $issueTypeScheme['name'], $currentDate);
