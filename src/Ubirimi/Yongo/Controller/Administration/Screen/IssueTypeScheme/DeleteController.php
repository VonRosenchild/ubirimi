<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $Id = $_POST['id'];
    $issueTypeScreenScheme = IssueTypeScreenScheme::getMetaDataById($Id);

    IssueTypeScreenScheme::deleteDataByIssueTypeScreenSchemeId($Id);
    IssueTypeScreenScheme::deleteById($Id);

    $currentDate = Util::getServerCurrentDateTime();
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'DELETE Yongo Issue Type Screen Scheme ' . $issueTypeScreenScheme['name'], $currentDate);