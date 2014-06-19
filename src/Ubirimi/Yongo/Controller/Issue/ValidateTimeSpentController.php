<?php
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $time = trim($_POST['time']);

    $valid = true;
    $time = str_replace(" ", '', $time);

    $count = '';
    $parts = '';
    for ($i = 0; $i < strlen($time); $i++) {
        if (!in_array($time[$i], array('w', 'd', 'h', 'm'))) {
            $count .= $time[$i];
        } else {
            if (!is_numeric($count)) {
                $valid = false;
                break;
            }
            $parts .= $time[$i];
            $count = '';
        }

        if ($count != '' && !is_numeric($count)) {
            $valid = false;
            break;
        }
    }

    for ($i = 0; $i < strlen($parts); $i++) {
        if (!in_array($parts[$i], array('w', 'd', 'h', 'm'))) {
            $valid = false;
            break;
        }
    }

    if ($valid)
        echo "ok";
    else
        echo "error";