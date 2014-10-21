<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

$index = 0;
echo '<td></td>';
echo '<td colspan="3">';

echo '<table width="100%">';
    echo '<tr>';
        echo '<td style="border: none">Version History</td>';
        echo '<td style="border: none">User</td>';
        echo '<td style="border: none">Date</td>';
        echo '</tr>';
    while ($revisions && $revision = $revisions->fetch_array(MYSQLI_ASSOC)) {
    $revisionNumber = $revisions->num_rows - $index;

    echo '<tr>';
        echo '<td style="border: none"><a href="/assets' . UbirimiContainer::get()['asset.documentador_filelists_attachments'] . $file['documentator_entity_id'] . '/' . $fileId . '/' . $revisionNumber . '/' . $file['name'] .  '">Version ' . ($revisions->num_rows - $index) . '</a></td>';
        echo '<td style="border: none">';
            if ($revisions->num_rows - $index == 1)
            echo 'Created by ';
            else
            echo 'Modified by ';
            echo LinkHelper::getUserProfileLink($revision['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $revision['first_name'], $revision['last_name']);
            echo '</td>';
        echo '<td style="border: none">' . Util::getFormattedDate($revision['date_created']) . '</td>';
        echo '</tr>';
    $index++;
    }
    echo '</table>';
echo '</td>';