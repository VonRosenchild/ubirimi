<?php


    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $key = $_GET['key'];
    $target = $_GET['target'];
    $mode = $_GET['mode'];

    $page = Entity::getById($key);
    $pages = Space::getChildrenPagesBySpaceIdAndPageId($page['space_id'], $page['id']);

    $pagesData = array();

    while ($pages && $page = $pages->fetch_array(MYSQLI_ASSOC)) {
        $isFolder = 0;
        if ($page['child_id'])
            $isFolder = 1;
        $pagesData[] = array('key' => $page['id'], 'title' => $page['name'], 'isLazy' => $isFolder, 'isFolder' => $isFolder, 'url' => '/documentador/page/view/' . $page['id']);
    }

    if ($target == 'tree')
    echo json_encode($pagesData);