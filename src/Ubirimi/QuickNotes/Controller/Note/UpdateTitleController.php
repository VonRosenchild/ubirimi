<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $id = $_POST['id'];
    $summary = $_POST['summary'];
    Note::updateTitleById($id, $summary);