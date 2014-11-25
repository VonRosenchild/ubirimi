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

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>

    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > ' . $board['name'] . ' > Sprint: ';
        if (isset($selectedSprint)) {
            $breadCrumb .= $selectedSprint['name'];
        } else {
            $breadCrumb .= 'No Sprint Completed';
        }
        $breadCrumb .= ' > Sprint Report';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px; float: right;">
            <li><a href="/agile/board/plan/<?php echo $boardId ?>" title="Plan">Plan</a></li>
            <li><a href="/agile/board/work/<?php if ($currentStartedSprint)
                    echo $currentStartedSprint['id'] . '/' . $boardId; else echo '-1/' . $boardId; ?>" title="Work">Work</a></li>
            <li class="active"><a href="/agile/board/report/<?php echo $sprintId ?>/<?php echo $boardId ?>/<?php echo $chart ?>" title="Report">Report</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <?php if (isset($selectedSprint)): ?>
                        <a id="report_agile_select_sprint" href="#" class="btn ubirimi-btn"><?php echo $selectedSprint['name'] ?> <img border="0" src="/img/br_down.png" style="padding-bottom: 2px;"/></a>
                    <?php else: ?>
                        <a id="report_agile_select_sprint" href="#" class="btn ubirimi-btn disabled">No Completed Sprint</a>
                    <?php endif ?>
                </td>
                <td>
                    <a id="report_agile_select_report" href="#" class="btn ubirimi-btn"><?php echo $availableCharts[$chart] ?> <img border="0" src="/img/br_down.png" style="padding-bottom: 2px;"/></a>
                </td>
            </tr>
        </table>

        <br/>
        <?php if (isset($selectedSprint)): ?>
            <?php if ($chart == 'sprint_report'): ?>
                <?php require_once __DIR__ . '/report/_report.php' ?>
            <?php elseif ($chart == 'velocity_chart'): ?>
                <div>Coming soon!</div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">There is no completed sprint.</div>
        <?php endif ?>

        <div id="submenu_completed_sprints" style="border: 1px solid #BBBBBB; z-index: 500; position: absolute; width: 200px; background-color: #ffffff; display: none; padding: 4px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
                <?php if ($completedSprints): ?>
                    <?php while ($completedSprint = $completedSprints->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <div><a class="linkSubMenu" href="/agile/board/report/<?php echo $completedSprint['id'] ?>/<?php echo $boardId ?>/sprint_report"><?php echo $completedSprint['name'] ?></a></div>
                            </td>
                        </tr>
                    <?php endwhile ?>
                <?php else: ?>
                    <tr>
                        <td>
                            <div><span class="linkSubMenu" href="#">No Sprints Available</span></div>
                        </td>
                    </tr>
                <?php endif ?>
            </table>
        </div>

        <div id="submenu_reports_available" style="border: 1px solid #BBBBBB; z-index: 500; position: absolute; width: 200px; background-color: #ffffff; display: none; padding: 4px; box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
                <?php foreach ($availableCharts as $key => $name): ?>
                    <?php if ($key != $chart): ?>
                        <tr>
                            <td>
                                <div><a class="linkSubMenu" href="/agile/board/report/<?php echo $sprintId ?>/<?php echo $boardId ?>/<?php echo $key ?>"><?php echo $name ?></a></div>
                            </td>
                        </tr>
                    <?php endif ?>
                <?php endforeach ?>
            </table>
        </div>
    </div>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>