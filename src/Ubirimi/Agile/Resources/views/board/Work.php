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

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > ' . $board['name'] . ' > Sprint: ';
        if ($sprint) {
            $breadCrumb .= $sprint['name'];
        } else {
            $breadCrumb .= 'No Active Sprint';
        }

        $breadCrumb .= ' > Work View';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px; float: right;">
            <li><a href="/agile/board/plan/<?php echo $boardId ?>" title="Plan">Plan</a></li>
            <li class="active"><a href="/agile/board/work/<?php echo $sprintId ?>/<?php echo $boardId ?>" title="Work">Work</a></li>
            <li><a href="/agile/board/report/<?php if ($lastCompletedSprint) echo $lastCompletedSprint['id']; else echo "-1"; ?>/<?php echo $boardId ?>/sprint_report" title="Report">Report</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>Quick Filters</td>
                <td>
                    <a id="btnAgileOnlyMyIssues" href="/agile/board/work/<?php echo $sprintId ?>/<?php echo $boardId ?><?php if ($onlyMyIssuesFlag == 0)
                        echo '?only_my=1' ?>" class="btn ubirimi-btn"><?php if ($onlyMyIssuesFlag) echo '<i class="icon-ok-sign"></i> ' ?>Only My Issues</a>
                </td>
                <?php if ($sprint): ?>
                    <td>
                        <div class="btn-group">
                            <a href="#" class="btn ubirimi-btn dropdown-toggle" data-toggle="dropdown">
                                <?php echo $sprint['name'] ?> Options <span class="caret"></span> </a>
                            <ul class="dropdown-menu">
                                <li><a id="submenu_close_sprint" href="#">Close Sprint</a></li>
                            </ul>
                        </div>
                    </td>
                <?php endif ?>
            </tr>
        </table>

        <table width="100%" border="0" cellspacing="0" align="center">
            <tr>
                <td valign="top" width="70%">
                    <div id="agile_wrapper_work" style="height: 500px; overflow-y: auto;">
                        <table width="100%" cellpadding="0" cellspacing="0px" border="0">
                            <?php require_once __DIR__ . '/work/_header.php' ?>
                        </table>
                        <?php
                            $index = 1;
                            if ($swimlaneStrategy == 'story' && $sprint) {
                                require_once __DIR__ . '/work/_swimlaneStory.php';
                            } else if ($swimlaneStrategy == 'assignee' && $sprint) {
                                require_once __DIR__ . '/work/_swimlaneAssignee.php';
                            } else if ($swimlaneStrategy == 'no_swimlane' && $sprint) {
                                require_once __DIR__ . '/work/_swimlaneDefault.php';
                            }
                        ?>
                    </div>
                </td>

                <td valign="top" width="30%" style="display: none" id="wrapper_content_agile_work">
                    <div id="content_agile_issue"></div>
                </td>
            </tr>
        </table>

        <?php if ($sprint === null): ?>
            <br/>

            <div class="infoBox">There are no active sprints.</div>
        <?php endif ?>
    </div>

    <?php
        $lastColumnStatuses = UbirimiContainer::get()['repository']->get(Board::class)->getColumnStatuses($columns[count($columns) - 1]['id'], 'array', 'id');
    ?>
    <div id="agileModalTransitionWithScreen"></div>
    <div id="agileModalUpdateParent"></div>
    <div id="agileCompleteSprint"></div>
    <div class="ubirimiModalDialog" id="modalAddSubTask"></div>
    <div class="ubirimiModalDialog" id="modalEditIssueAssign"></div>

    <input type="hidden" value="<?php echo $boardId ?>" id="board_id"/> <input type="hidden" value="<?php echo $sprintId ?>" id="sprint_id"/> <input type="hidden" value="<?php echo count($columns); ?>" id="count_columns"/>
    <input type="hidden" value="<?php echo $index; ?>" id="max_index_section"/> <input type="hidden" value="<?php echo $swimlaneStrategy; ?>" id="agile_swimlane_strategy"/>
    <input type="hidden" value="<?php echo implode('_', $lastColumnStatuses); ?>" id="last_column_statuses"/>
</body>