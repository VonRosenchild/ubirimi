<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $date = Util::getServerCurrentDateTime();

    $value = $_POST['value'];
    $noteId = $_POST['id'];

    // check for duplicates in the user space
    $tagUserExists = Tag::getByNameAndUserId($loggedInUserId, mb_strtolower($value));

    if ($tagUserExists) {
        // check if it is already added to the note
        $tagNoteExists = Note::getTagByTagIdAndNoteId($noteId, $tagUserExists['id']);
        if (!$tagNoteExists) {
            Note::addTag($noteId, $tagUserExists['id'], $date);
            echo "1";
            die();
        }
        echo "0";
    } else {
        $tagId = Tag::add($loggedInUserId, $value, $date);
        Note::addTag($noteId, $tagId, $date);
        echo "1";
    }