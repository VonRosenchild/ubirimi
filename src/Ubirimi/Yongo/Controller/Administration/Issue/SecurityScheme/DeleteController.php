<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeId = $_POST['id'];

    IssueSecurityScheme::deleteById($issueSecuritySchemeId);