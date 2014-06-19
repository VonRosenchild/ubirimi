<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();
    $screenDataId = $_POST['screen_data_id'];

    Screen::deleteDataById($screenDataId);