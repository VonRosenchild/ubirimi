<?php



    $spaceId = $_POST['id'];

    $space = Space::getById($spaceId);

    $homePage = Entity::getById($space['home_entity_id'], null, 0);
    $pages = Space::getChildrenPagesBySpaceIdAndPageId($spaceId, $space['home_entity_id']);

    $pagesData = array();

    if ($homePage) {
        $children = Space::getChildrenPagesBySpaceIdAndPageId($spaceId, $homePage['id']);

        $isFolder = 0;
        if ($children)
            $isFolder = 1;
        $pagesData[] = array('parent' => 0, 'key' => $space['home_entity_id'], 'title' => $homePage['name'], 'isFolder' => $isFolder, 'url' => '/documentador/page/view/' . $space['home_entity_id']);

        $pages = Space::getAllBySpaceIdNoExistingParent($spaceId);

        while ($pages && $page = $pages->fetch_array(MYSQLI_ASSOC)) {
            $isFolder = 0;
            $pagesData[] = array('parent' => 0, 'key' => $page['id'], 'title' => $page['name'], 'isFolder' => 0, 'url' => '/documentador/page/view/' . $page['id']);
        }

    }
    while ($pages && $page = $pages->fetch_array(MYSQLI_ASSOC)) {
        $isFolder = 0;
        if ($page['child_id'])
            $isFolder = 1;
        $pagesData[] = array('parent' => $page['parent_entity_id'], 'key' => $page['id'], 'title' => $page['name'], 'isFolder' => $isFolder, 'url' => '/documentador/page/view/' . $page['id']);
    }

    echo json_encode($pagesData);