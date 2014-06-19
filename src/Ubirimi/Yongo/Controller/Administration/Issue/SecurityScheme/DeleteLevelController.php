<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\Issue;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeLevelId = $_POST['id'];
    $newIssueSecuritySchemeLevelId = $_POST['new_level_id'];

    Issue::updateSecurityLevel($clientId, $issueSecuritySchemeLevelId, $newIssueSecuritySchemeLevelId);
    IssueSecurityScheme::deleteLevelById($issueSecuritySchemeLevelId);