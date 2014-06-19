<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $Id = $_POST['id'];

    IssueTypeScheme::deleteDataByIssueTypeSchemeId($Id);
    IssueTypeScheme::deleteById($Id);