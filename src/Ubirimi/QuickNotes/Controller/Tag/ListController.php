<?php
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);
    $menuSelectedCategory = 'tags';
    $tags = Tag::getByUserId($loggedInUserId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME . ' / tags';

    require_once __DIR__ . '/../../Resources/views/Tag/List.php';