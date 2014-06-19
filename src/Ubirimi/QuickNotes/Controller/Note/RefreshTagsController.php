<?php
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $notebookId = $_POST['notebook_id'];
    $allTags = Tag::getAll($loggedInUserId);

    require_once __DIR__ . '/../../Resources/views/Note/RefreshTagsController.php';