<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $notebookId = $_GET['id'];
    $notebook = Notebook::getById($notebookId);

    if ($notebook['user_id'] != $loggedInUserId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $notebookExists = false;

    if (isset($_POST['edit_notebook'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication

        $notebookDuplicate = Notebook::getByName($loggedInUserId, mb_strtolower($name), $notebookId);
        if ($notebookDuplicate) {
            $notebookExists = true;
        }
        if (!$notebookExists && !$emptyName) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Notebook::updateById($notebookId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_QUICK_NOTES, $loggedInUserId, 'UPDATE NOTEBOOK notebook ' . $name, $date);

            header('Location: /quick-notes/my-notebooks');
        }
    }

    $menuSelectedCategory = 'notebooks';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME . ' / Notebook: ' . $notebook['name'] . ' / Update';

    require_once __DIR__ . '/../../Resources/views/Notebook/Edit.php';