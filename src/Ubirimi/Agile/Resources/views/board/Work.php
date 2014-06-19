<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>

    <div class="pageContent">
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
                    <div id="agile_wrapper_work" style="height: 500px; overflow-y: auto; padding-left: 4px; padding-right: 4px;">
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
                                require_once __DIR__ . '/work/_swimlane_default.php';
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
        $lastColumnStatuses = AgileBoard::getColumnStatuses($columns[count($columns) - 1]['id'], 'array', 'id');
    ?>
    <div id="agileModalTransitionWithScreen"></div>
    <div id="agileModalUpdateParent"></div>
    <div id="agileCompleteSprint"></div>
    <div id="modalAddSubTask"></div>
    <div id="modalEditIssueAssign"></div>

    <input type="hidden" value="<?php echo $boardId ?>" id="board_id"/> <input type="hidden" value="<?php echo $sprintId ?>" id="sprint_id"/> <input type="hidden" value="<?php echo count($columns); ?>" id="count_columns"/>
    <input type="hidden" value="<?php echo $index; ?>" id="max_index_section"/> <input type="hidden" value="<?php echo $swimlaneStrategy; ?>" id="agile_swimlane_strategy"/>
    <input type="hidden" value="<?php echo implode('_', $lastColumnStatuses); ?>" id="last_column_statuses"/>
</body>