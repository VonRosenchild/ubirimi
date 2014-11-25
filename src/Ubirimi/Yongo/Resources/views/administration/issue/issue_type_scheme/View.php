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

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/issue-type-schemes" class="linkNoUnderline">Issue Type Schemes</a> > ' . $issueTypeScheme['name'];
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Options</th>
                    <th align="left">Projects</th>
                </tr>
            </thead>
            <tbody>
            <tr id="table_row_<?php echo $issueTypeScheme['id'] ?>">
                <td>
                    <?php
                        echo '<div>' . $issueTypeScheme['name'] . '</div>';
                        echo '<div class="smallDescription">' . $issueTypeScheme['description'] . '</div>';
                    ?>
                </td>
                <td>
                    <?php
                        $dataIssueTypeScheme = UbirimiContainer::get()['repository']->get(IssueTypeScheme::class)->getDataById($issueTypeScheme['id']);
                        while ($data = $dataIssueTypeScheme->fetch_array(MYSQLI_ASSOC)) {
                            echo '<div>' . $data['name'] . '</div>';
                        }
                    ?>
                </td>
                <td valign="top">
                    <?php
                        $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByIssueTypeScheme($issueTypeScheme['id']);
                        if ($projects) {
                            echo '<ul>';
                            while ($project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                echo '<li><a href="/yongo/administration/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                            }
                            echo '</ul>';
                        }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>