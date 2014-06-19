<?php
    use Ubirimi\Repository\Documentador\EntityComment;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $commentId = $_POST['id'];

    EntityComment::deleteById($commentId);