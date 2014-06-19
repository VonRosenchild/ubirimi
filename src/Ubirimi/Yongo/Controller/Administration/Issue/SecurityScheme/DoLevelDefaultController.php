<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $securityLevelId = $_GET['id'];
    $securityLevel = IssueSecurityScheme::getLevelById($securityLevelId);
    $securityScheme = IssueSecurityScheme::getMetaDataById($securityLevel['issue_security_scheme_id']);
    IssueSecurityScheme::makeAllLevelsNotDefault($securityScheme['id']);
    IssueSecurityScheme::setLevelDefault($securityLevelId);

    header('Location: /yongo/administration/issue-security-scheme-levels/' . $securityScheme['id']);