<?php


    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $pageId = $_POST['id'];

    $childPages = Entity::getChildren($pageId);
    $comments = EntityComment::getComments($pageId, 'array');

    if ($comments) {

        $htmlLayout = '';
        if ($childPages) {
            $htmlLayout .= '<br />';
        }
        $pluralCommentsHTML = '';
        if (count($comments) > 1)
            $pluralCommentsHTML = 's';

        $htmlLayout .= '<div class="headerPageText" style="border-bottom: 1px solid #DDDDDD;">' . count($comments) . ' Comment' . $pluralCommentsHTML . '</div>';
        $htmlLayout .= '<div style="float: left; display: block; width: 100%">';
        $htmlLayout = EntityComment::getCommentsLayoutHTML($comments, $htmlLayout, null, 0);
        echo $htmlLayout;
        $htmlLayout .= '</div>';
    }