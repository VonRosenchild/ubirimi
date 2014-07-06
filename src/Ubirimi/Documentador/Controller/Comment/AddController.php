<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityComment;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $content = Util::cleanRegularInputField($_POST['content']);
    $pageId = $_POST['entity_id'];
    $parentId = $_POST['parent_comment_id'];
    $date = Util::getServerCurrentDateTime();

    EntityComment::addComment($pageId, $loggedInUserId, $content, $date, $parentId);