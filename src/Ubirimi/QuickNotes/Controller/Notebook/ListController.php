<?php
    use Ubirimi\Calendar\Repository\Calendar;
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $session->set('selected_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES);
    $menuSelectedCategory = 'notebooks';
    $notebooks = Notebook::getByUserId($loggedInUserId);

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME . ' / My Notebooks';

    require_once __DIR__ . '/../../Resources/views/Notebook/List.php';