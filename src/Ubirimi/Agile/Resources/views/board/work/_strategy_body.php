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
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Yongo\Repository\Workflow\Workflow;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Field\Field;

?>
<table width="100%" cellpadding="0px" cellspacing="0px" border="0" class="agile_work_<?php echo $index ?>">
    <?php if ($swimlaneStrategy == 'assignee'): ?>
        <?php require '_parent_assignee_box.php' ?>
    <?php elseif ($swimlaneStrategy == 'story'): ?>
        <?php require '_parent_story_box.php' ?>
    <?php endif ?>
    <tr id="agile_content_columns">
        <?php for ($i = 0; $i < count($columns); $i++): ?>
            <?php
                if ($swimlaneStrategy == 'assignee') {
                    $lastParentId = -1;
                    $lastIssueId = -1;
                }
            ?>
            <td width="<?php echo (100 / count($columns)) ?>%" id="column_data_<?php echo $columns[$i]['id'] . '_' . $index ?>" valign="top" class="droppableAgileColumn_<?php echo $index ?> pageContentSmall">
                <?php
                    $statuses = UbirimiContainer::get()['repository']->get(Board::class)->getColumnStatuses($columns[$i]['id'], 'array');
                ?>
                <div style="display: none; position: absolute; margin: 4px;" id="statuses_for_column_<?php echo $columns[$i]['id'] . '_' . $index ?>">
                    <?php for ($j = 0; $j < count($statuses); $j++): ?>
                        <div id="status_for_column_<?php echo $columns[$i]['id'] . '_' . $statuses[$j]['id'] . '_' . $index ?>" class="status_for_column_<?php echo $index . '_' . $columns[$i]['id']; ?>" style="border: 4px dashed #b3b3b3; border-radius: 10px" align="center" class="headerPageText"><?php echo $statuses[$j]['name'] ?></div>
                    <?php endfor ?>
                </div>
                <?php if ($strategyIssue): ?>
                    <?php $strategyIssue->data_seek(0); ?>
                    <?php while ($issue = $strategyIssue->fetch_array(MYSQLI_ASSOC)): ?>
                        <?php
                            if (!in_array($issue['status'], Util::array_column($statuses, 'id'))) {
                                continue;
                            }
                            $workflowUsed = UbirimiContainer::get()['repository']->get(YongoProject::class)->getWorkflowUsedForType($issue['issue_project_id'], $issue['type']);
                            $stepWorkflowFrom = UbirimiContainer::get()['repository']->get(Workflow::class)->getStepByWorkflowIdAndStatusId($workflowUsed['id'], $issue[Field::FIELD_STATUS_CODE]);
                            $parentId = -1;
                            if ($issue['parent_id']) {
                                $parentId = $issue['parent_id'];
                            }
                        ?>
                        <div style="width: 100%" id="issue_in_column_<?php echo $columns[$i]['id'] . '_' . $issue['id'] . '_' . $issue['issue_project_id'] . '_' . $stepWorkflowFrom['id'] . '_' . $workflowUsed['id'] . '_' . $index . '_' . $parentId; ?>" class="draggableIssueAgileBasic draggableIssueAgile_<?php echo $index ?>">
                            <table width="100%" cellspacing="0px" cellpadding="0px" border="0px">
                                <?php if ($swimlaneStrategy == 'assignee'): ?>
                                    <?php if ($lastParentId != $issue['parent_id'] && $lastIssueId != $issue['parent_id']): ?>
                                        <?php if (($issue['parent_status_id'] && $issue['parent_status_id'] != $issue['status']) || ($issue['parent_assignee'] == null && $issue['assignee'] != null && $issue['parent_id']) || ($issue['parent_assignee'] && $issue['assignee'] != $issue['parent_assignee'])): ?>
                                            <tr>
                                                <td bgcolor="#DDDDDD" colspan="4"><?php echo $issue['parent_project_code'] . '-' . $issue['parent_nr'] . ' ' . $issue['parent_summary'] ?></td>
                                            </tr>
                                        <?php endif ?>
                                    <?php endif ?>
                                <?php endif ?>
                                <tr>
                                    <?php if ($swimlaneStrategy == 'assignee'): ?>
                                        <?php if ($issue['parent_id']): ?>
                                            <td width="10px"></td>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <td width="10px" bgcolor="<?php echo $issue['priority_color'] ?>"></td>
                                    <td>
                                        <div style="padding-left: 4px">
                                            <div>
                                                <img src="/yongo/img/issue_type/<?php echo $issue['issue_type_icon_name'] ?>" height="16px" alt="">
                                                <a href="#" id="agile_issue_<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . ' ' . $issue['nr'] ?></a>
                                            </div>
                                            <div><img src="/yongo/img/issue_priority/<?php echo $issue['issue_priority_icon_name'] ?>" height="16px" alt=""> <?php echo $issue['summary'] ?></div>
                                        </div>
                                    </td>
                                    <td align="center" width="40px">
                                        <img src="<?php
                                        echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture(array('id' => $issue['assignee'], 'avatar_picture' => $issue['assignee_avatar_picture']), 'small') ?>" title="<?php echo $issue['ua_first_name'] . ' ' . $issue['ua_last_name'] ?>" height="33px" style="vertical-align: middle;" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                            if ($swimlaneStrategy == 'assignee') {
                                $lastParentId = $issue['parent_id'];
                                $lastIssueId = $issue['id'];
                            }
                        ?>
                    <?php endwhile ?>
                <?php endif ?>
                <br />
                <br />
                <br />
                <br />
            </td>
            <?php if ($i != (count($columns) - 1)): ?>
                <td>
                    <div>&nbsp;&nbsp;</div>
                </td>
            <?php endif ?>
        <?php endfor ?>
    </tr>
</table>