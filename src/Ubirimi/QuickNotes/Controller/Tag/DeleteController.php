<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $tagId = $_POST['id'];
    $tag = Tag::getById($tagId);
    $date = Util::getServerCurrentDateTime();

    Tag::deleteById($tagId);

    Log::add($clientId, SystemProduct::SYS_PRODUCT_QUICK_NOTES, $loggedInUserId, 'DELETE QUICK NOTES tag  ' . $tag['name'], $date);