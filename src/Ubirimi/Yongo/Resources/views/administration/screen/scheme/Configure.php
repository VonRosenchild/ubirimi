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

use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/screens/schemes">Screen Schemes</a> > ' . $screenScheme['name'] . ' > Configure';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <?php if ($source == 'project'): ?>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/<?php echo $projectId ?>">Go Back</a>
                    <?php else: ?>
                        <a class="btn ubirimi-btn" href="/yongo/administration/screens/schemes">Go Back</a>
                    <?php endif ?>
                </td>
                <td><a id="btnEditScreenSchemeData" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
            </tr>
        </table>

        <?php if ($screenSchemeData): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th width="200">Issue Operation</th>
                        <th>Screen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data = $screenSchemeData->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $data['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $data['id'] ?>"/></td>
                            <td><?php echo ucfirst($data['operation_name']) . ' Issue'; ?></td>
                            <td><a href="/yongo/administration/screen/configure/<?php echo $data['screen_id'] ?>"><?php echo $data['screen_name']; ?></a></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="messageGreen"></div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>