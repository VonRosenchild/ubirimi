<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $ids = $_POST['id'];

    AgileBoard::deleteIssuesFromSprints($ids);