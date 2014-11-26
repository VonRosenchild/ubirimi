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
use Ubirimi\Agile\Repository\Sprint\Sprint;
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > ' . $board['name'] . ' > Plan View';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" width="100%">
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" class="tableButtons">
                        <tr>
                            <td>
                                <input type="text" id="agile_quick_search" value="<?php echo $searchQuery ?>" class="inputText" style="width: 150px; background: url(/img/find.png) right no-repeat;"/>
                            </td>
                            <td>Quick Filters</td>
                            <td>
                                <?php
                                    $parametersLink = array();
                                    if ($onlyMyIssuesFlag == 0)
                                        $parametersLink[] = 'only_my=1';
                                    if ($searchQuery)
                                        $parametersLink[] = 'q=' . $searchQuery;
                                ?>
                                <a id="btnAgileOnlyMyIssues" href="/agile/board/plan/<?php echo $boardId ?><?php if (count($parametersLink))
                                    echo '?' . implode('&', $parametersLink); ?>" class="btn ubirimi-btn"><?php if ($onlyMyIssuesFlag)
                                        echo '<i class="icon-ok"></i>' ?> Only My Issues</a>
                            </td>
                            <td>
                                <a id="btnAddSprint" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Sprint</a>
                            </td>

                            <td>
                                <div class="btn-group" style="float: right;">
                                    <a id="btnAddToSprint" href="#" class="btn ubirimi-btn dropdown-toggle disabled" data-toggle="dropdown">Add to Sprint <span class="caret"></span> </a>

                                    <?php if ($sprintsNotStarted || $currentStartedSprint): ?>
                                        <?php if ($sprintsNotStarted)
                                            $sprintsNotStarted->data_seek(0); ?>
                                        <ul class="dropdown-menu">
                                            <?php if ($currentStartedSprint): ?>
                                                <li><a id="add_to_sprint_<?php echo $currentStartedSprint['id']; ?>" href="#"><?php echo $currentStartedSprint['name'] ?></a></li>
                                            <?php endif ?>
                                            <?php while ($sprintsNotStarted && $sprint = $sprintsNotStarted->fetch_array(MYSQLI_ASSOC)): ?>
                                                <?php if (!$sprint['started_flag']): ?>
                                                    <li><a id="add_to_sprint_<?php echo $sprint['id']; ?>" href="#"><?php echo $sprint['name'] ?></a></li>
                                                <?php endif ?>
                                            <?php endwhile ?>
                                        </ul>
                                    <?php endif ?>
                                </div>
                            </td>
                            <td>
                                <a id="btnMoveToBacklog" href="#" class="btn ubirimi-btn disabled">Move to Backlog</a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="right">
                    <ul class="nav nav-tabs" style="padding: 0px; float: right;">
                        <li class="active"><a href="/agile/board/plan/<?php echo $boardId ?>" title="Plan">Plan</a></li>
                        <li><a href="/agile/board/work/<?php if ($currentStartedSprint) echo $currentStartedSprint['id'] . '/' . $boardId; else echo '-1/' . $boardId; ?>" title="Work">Work</a></li>
                        <li><a href="/agile/board/report/<?php if ($lastCompletedSprint) echo $lastCompletedSprint['id']; else echo "-1"; ?>/<?php echo $boardId ?>/sprint_report" title="Report">Report</a></li>
                    </ul>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="">
            <tr>
                <td width="70%" valign="top" style="padding-right: 10px">
                    <div id="agile_wrapper_planning" class="pageContentNoPadding" style="height: 500px; overflow-y: auto; border-radius: 0px">
                        <?php if ($currentStartedSprint): ?>
                            <div style="padding-bottom: 4px;" class="droppablePlanSprint">
                                <span class="headerPageText"><?php echo $currentStartedSprint['name'] ?></span>
                                <?php
                                    $issues = UbirimiContainer::get()['repository']->get(Sprint::class)->getIssuesBySprintId($currentStartedSprint['id'], $onlyMyIssuesFlag, $loggedInUserId, $searchQuery);
                                    $renderCheckboxDisabled = true;

                                    $params = array('issues' => $issues, 'render_checkbox' => true, 'checkbox_disabled' => $renderCheckboxDisabled, 'show_header' => false);
                                    Util::renderIssueTables($params, $columns, $clientSettings);
                                ?>
                            </div>
                        <?php endif ?>

                        <?php
                            $sprintStartedTextRendered = false;
                            if ($sprintsNotStarted) {
                                $sprintsNotStarted->data_seek(0);
                            }
                            while ($sprintsNotStarted && $sprint = $sprintsNotStarted->fetch_array(MYSQLI_ASSOC)) {

                                $issues = UbirimiContainer::get()['repository']->get(Sprint::class)->getIssuesBySprintId($sprint['id'], $onlyMyIssuesFlag, $loggedInUserId, $searchQuery);

                                $sprintHasIssues = $issues && $issues->num_rows;

                                echo '<div style="padding-bottom: 4px;">';
                                echo '<span class="headerPageText">' . $sprint['name'] . '</span>';
                                echo '<span style="float: right">';
                                if ($currentStartedSprint && !$sprintStartedTextRendered) {
                                    echo '<span>Start Sprint</span> | ';
                                    $sprintStartedTextRendered = true;
                                }

                                if (!$currentStartedSprint && $sprint['started_flag'] == 0 && !$sprintStartedTextRendered) {
                                    if ($sprintHasIssues)
                                        echo '<a href="#" id="start_sprint_' . $sprint['id'] . '">Start Sprint</a> | '; else
                                        echo '<span>Start Sprint</span> | ';
                                    $sprintStartedTextRendered = true;
                                }
                                if ($sprint['started_flag'] == 0) {
                                    echo ' <a id="delete_sprint_' . $sprint['id'] . '" href="#">Delete</a>';
                                }
                                echo '</span>';
                                echo '</div>';

                                if ($sprintHasIssues) {
                                    $renderCheckboxDisabled = false;
                                    if ($sprint['started_flag'])
                                        $renderCheckboxDisabled = true;

                                    $params = array('issues' => $issues, 'render_checkbox' => true, 'checkbox_disabled' => $renderCheckboxDisabled, 'show_header' => false);
                                    Util::renderIssueTables($params, $columns, $clientSettings);
                                    echo '<div style="padding-bottom: 4px"></div>';
                                } else {
                                    echo '<div class="messageGreen">There are no issues for this sprint.</div>';
                                }
                            }

                            $issues = UbirimiContainer::get()['repository']->get(Board::class)->getBacklogIssues($clientId, $board, $onlyMyIssuesFlag, $loggedInUserId, $searchQuery, $completeStatuses);

                            $params = array('issues' => $issues, 'render_checkbox' => true, 'show_header' => false, 'in_backlog' => true);
                            echo '<div class="headerPageText" style="padding-bottom: 4px; padding-top: 4px">Backlog</div>';
                            if (isset($issues) && $issues->num_rows > 0) {
                                Util::renderIssueTables($params, $columns, $clientSettings);
                            } else {
                                echo '<div class="messageGreen">There are no issues for the backlog.</div>';
                            }
                        ?>
                    </div>
                </td>
                <td width="30%" valign="top">
                    <div id="agileIssueContent" class="pageContentNoPadding" style="border-radius: 0px; overflow: auto">
                        <div class="headerPageText">Plan Mode</div>
                        <div>Plan mode is where you scrub your backlog: review, estimate, and prioritise your stories and bugs, then create and plan sprints (iterations of work).</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <input type="hidden" value="agile" id="contextList"/> <input type="hidden" value="<?php if ($sprintsNotStarted || $currentStartedSprint)
        echo '1'; else echo '0'; ?>" id="add_to_sprint_possible"/> <input type="hidden" value="<?php echo $boardId ?>" id="board_id"/>

    <div class="ubirimiModalDialog" id="modalAddSprint"></div>
    <div class="ubirimiModalDialog" id="modalDeletePlannedSprint"></div>
    <div class="ubirimiModalDialog" id="modalSprintStart"></div>
    <div class="ubirimiModalDialog" id="modalAddSubTask"></div>
    <div class="ubirimiModalDialog" id="modalEditIssueAssign"></div>
</body>