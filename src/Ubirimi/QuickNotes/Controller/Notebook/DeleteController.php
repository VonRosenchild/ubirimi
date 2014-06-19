<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $notebookId = $_POST['id'];
    $notebook = Notebook::getById($notebookId);

    $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

    Notebook::deleteById($notebookId);

    Log::add($clientId, SystemProduct::SYS_PRODUCT_QUICK_NOTES, $loggedInUserId, 'DELETE QUICK NOTES notebook ' . $notebook['name'], $date);