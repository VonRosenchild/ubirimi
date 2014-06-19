<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $notificationSchemeDataId = $_GET['notification_scheme_data_id'];
?>
Are you sure you want to delete this notification?