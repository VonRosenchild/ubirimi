<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $spaceId = $_POST['space_id'];
    $pageNameKeyword = $_POST['page'];

    $pages = Entity::findBySpaceIdAndKeyword($clientId, $spaceId, $pageNameKeyword);

    if ($pages) {
        echo '<div id="" style="overflow-y: scroll; height: 500px;">';
            echo '<table style="width: 100%; padding: 4px;">';
                echo '<tr>';
                    echo '<td width="70%" style="padding: 4px; cursor: pointer; background-color: #B2DFEE; text-decoration: none;">Name</td>';
                    echo '<td width="15%" style="cursor: pointer; background-color: #B2DFEE; text-decoration: none;">Space</td>';
                    echo '<td width="15%" style="cursor: pointer; background-color: #B2DFEE; text-decoration: none;">Added by</td>';
                echo '</tr>';
            while ($page = $pages->fetch_array(MYSQLI_ASSOC)) {
                echo '<tr class="dialog_cell" id="doc_dialog_page_select_' . $page['id'] . '" data="/documentador/page/view/' . $page['id'] . '">';
                    echo '<td width="70%" style="cursor: pointer; padding: 4px; border-bottom: 1px solid #DDDDDD;">' . $page['name'] . '</td>';
                    echo '<td width="15%" style="cursor: pointer; padding: 4px; border-bottom: 1px solid #DDDDDD;">' . $page['space_name'] . '</td>';
                    echo '<td width="15%" style="cursor: pointer; padding: 4px; border-bottom: 1px solid #DDDDDD;">' . $page['first_name'] . ' ' . $page['last_name'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        echo '</div>';
    } else {
        echo '<div>There are no pages found.</div>';
    }