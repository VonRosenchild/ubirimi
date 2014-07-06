<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $duplicateName = false;

    $name = Util::cleanRegularInputField($_POST['name']);
    $description = Util::cleanRegularInputField($_POST['description']);

    if (empty($name)) {
        $emptyName = true;
    }

    $notebookSameName = Notebook::getByName($loggedInUserId, $name);
    if ($notebookSameName) {
        $duplicateName = true;
    }

    if (!$emptyName && !$duplicateName) {
        $currentDate = Util::getServerCurrentDateTime();
        $notebookId = Notebook::save($loggedInUserId, $name, $description, $currentDate);

        Log::add($clientId, SystemProduct::SYS_PRODUCT_CALENDAR, $loggedInUserId, 'ADD QUICK NOTES notebook ' . $name, $currentDate);
    }