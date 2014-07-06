<?php
    use Ubirimi\QuickNotes\Repository\Note;
    use Ubirimi\QuickNotes\Repository\Tag;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $date = Util::getServerCurrentDateTime();

    $name = $_POST['name'];
    $description = $_POST['description'];

    // check for duplicates in the user space
    $tagUserExists = Tag::getByNameAndUserId($loggedInUserId, mb_strtolower($name));

    if (!$tagUserExists) {
        $tagId = Tag::add($loggedInUserId, $name, $date);
        echo "1";
    } else {
        echo "0";
    }