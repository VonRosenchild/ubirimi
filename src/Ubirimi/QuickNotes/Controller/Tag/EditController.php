<?php
    use Ubirimi\QuickNotes\Repository\Notebook;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $tagId = $_GET['id'];
    $tag = Tag::getById($tagId);

    if ($tag['user_id'] != $loggedInUserId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $tagExists = false;

    if (isset($_POST['edit_tag'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        // check for duplication

        $tagDuplicate = Tag::getByNameAndUserId($loggedInUserId, mb_strtolower($name), $tagId);
        if ($tagDuplicate) {
            $tagExists = true;
        }
        if (!$tagExists && !$emptyName) {
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            Tag::updateById($tagId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_QUICK_NOTES, $loggedInUserId, 'UPDATE NOTEBOOK tag ' . $name, $date);

            header('Location: /quick-notes/tag/all');
        }
    }

    $menuSelectedCategory = 'tags';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_QUICK_NOTES_NAME . ' / Notebook: ' . $tag['name'] . ' / Update';

    require_once __DIR__ . '/../../Resources/views/Tag/Edit.php';