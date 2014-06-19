<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeLevelDataId = $_POST['id'];
    $issueSecuritySchemeLevelData = IssueSecurityScheme::getLevelDataById($issueSecuritySchemeLevelDataId);
    $issueSecuritySchemeLevelId = $issueSecuritySchemeLevelData['issue_security_scheme_level_id'];
    $issueSecuritySchemeLevel = IssueSecurityScheme::getLevelById($issueSecuritySchemeLevelId);

    IssueSecurityScheme::deleteLevelDataById($issueSecuritySchemeLevelDataId);

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
    Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Security Scheme Level ' . $issueSecuritySchemeLevel['name'], $date);