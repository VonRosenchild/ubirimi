<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $revisionNR = $_GET['rev_nr'];

    echo 'Are you sure you want to revert the page content back to this previous version (v. ' . $revisionNR . ')?';