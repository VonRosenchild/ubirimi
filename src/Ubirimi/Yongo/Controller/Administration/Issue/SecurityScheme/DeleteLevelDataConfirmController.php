<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $issueSecuritySchemeLevelDataId = $_GET['id'];
    echo 'Are you sure you want to delete this Issue Security level information?';