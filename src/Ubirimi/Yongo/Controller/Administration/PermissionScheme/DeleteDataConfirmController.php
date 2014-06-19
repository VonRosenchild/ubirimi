<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $permission_scheme_data_id = $_GET['id'];
?>
Are you sure you want to delete this permission?