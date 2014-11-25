<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>

    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > ' . $board['name'] . ' > Configure') ?>

    <div class="pageContent">
        <form name="add_board" action="/agile/board/add" method="post">

            <table cellspacing="4px">
                <tr>
                    <td>Name</td>
                    <td><?php echo $board['name'] ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo $board['description'] ?></td>
                </tr>
                <tr>
                    <td>Created by</td>
                    <td><?php echo LinkHelper::getUserProfileLink($board['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $board['first_name'], $board['last_name']) ?></td>
                </tr>
            </table>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active"><a href="/agile/configure-board/<?php echo $boardId ?>" title="Summary">Filter</a></li>
                <li><a href="/agile/edit-board-columns/<?php echo $boardId ?>" title="Issues">Columns</a></li>
                <li><a href="/agile/board-swimlane/<?php echo $boardId ?>" title="Issues">Swimlanes</a></li>
            </ul>

            <table width="100%" cellpadding="8px">
                <tr>
                    <td valign="top" width="100px">Saved Filter</td>
                    <td>
                        <?php echo $board['filter_name'] ?>
                        <div><a href="/yongo/issue/search?filter=<?php echo $board['filter_id'] ?>&<?php echo $filter['definition'] ?>">View in Issue Navigator</a></div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Projects</td>
                    <td>
                        <?php
                            if ($boardProjects) {
                                for ($i = 0; $i < count($boardProjects); $i++) {
                                    echo '<div><a href="/yongo/project/' . $boardProjects[$i]['id'] . '">' . $boardProjects[$i]['name'] . '</a></div>';
                                }
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>