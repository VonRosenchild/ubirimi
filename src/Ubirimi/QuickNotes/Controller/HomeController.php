<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'notebooks';

    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Quick Notes';
    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);

    $notebooks = Notebook::getByUserId($loggedInUserId, 'array');
    require_once __DIR__ . '/../Resources/views/Home.php';