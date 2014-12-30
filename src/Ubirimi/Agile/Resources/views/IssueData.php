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
use Ubirimi\Yongo\Repository\Field\Field;

?>
<div style="width: 100%; height: 100%">

    <table width="100%" cellpadding="2" cellspacing="0">
        <tr>
            <td width="10px" bgcolor="<?php echo $issue['priority_color'] ?>"></td>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="headerPageText">
                                <a class="linkNoUnderline" href="/yongo/project/<?php echo $projectId ?>"><?php echo $issueProject['name'] ?></a> / <?php echo $issue['project_code'] . '-' . $issue['nr'] ?>
                            </div>
                            <div class="issueSummaryTitle"><?php echo $issue['summary'] ?></div>
                        </td>
                        <td align="right" valign="top">

                            <div class="btn-group">
                                <a id="btnOptions" href="#" class="btn ubirimi-btn dropdown-toggle" data-toggle="dropdown">Options <span class="caret"></span></a>

                                <ul class="dropdown-menu pull-right">
                                    <?php if ($issue['assignee'] != $loggedInUserId): ?>
                                        <li><a id="agile_plan_assign_to_me_<?php echo $issueId ?>" href="#">Assign to Me</a></li>
                                    <?php endif ?>
                                    <li><a id="agile_plan_assign_other_<?php echo $issueId ?>_<?php echo $projectId ?>_<?php echo $issue['assignee'] ?>" href="#">Assign</a></li>
                                </ul>
                            </div>
                        </td>
                        <?php if ($close): ?>
                            <td align="right" valign="top" width="20px">
                                <img id="close_agile_issue_content" style="margin-top: 4px"  src="/img/close.png" width="20px" />
                            </td>
                        <?php endif ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="height: 2px"></div>
    <ul class="nav nav-tabs" style="padding: 0px;">
        <li id="tab_issue_agile_content_basic" class="active"><a href="#" title="Summary">Info</a></li>
        <li id="tab_issue_agile_content_descr"><a href="#" title="Description">Descr</a></li>
        <li id="tab_issue_agile_content_comments"><a href="#" title="Comments">Comments</a></li>
        <li id="tab_issue_agile_content_attachments"><a href="#" title="Attachments">Att</a></li>
        <li id="tab_issue_agile_content_subtasks"><a href="#" title="Sub-Tasks">Sub-Tasks</a></li>
    </ul>
    <div style="height: 8px"></div>
    <div id="content_issue_agile_content_basic">
        <table width="100%" id="details1" cellpadding="0">
            <tr>
                <td></td>
            </tr>

            <tr>
                <td id="sectDescription" class="sectionDetail" colspan="2">
                    <span class="sectionDetailTitle headerPageTextSmall">Details</span>
                </td>
            </tr>
            <tr>
                <td width="120"><div class="textLabel">Priority:</div></td>
                <td><?php echo $issue['priority_name'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Type:</div></td>
                <td><?php echo $issue['type_name']; ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Status:</div></td>
                <td colspan="3">
                    <?php echo $issue['status_name'] ?>
                    <?php if ($issue[Field::FIELD_RESOLUTION_CODE]): ?>
                        <span>(<?php echo $issue['resolution_name'] ?>)</span>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td width="124" valign="top">
                    <div class="textLabel">Components:</div>
                </td>
                <td>
                    <?php $indexComponents = 1 ?>
                    <?php if ($components): ?>
                        <?php while ($component = $components->fetch_array(MYSQLI_ASSOC)): ?>
                            <span>
                                    <?php
                                        echo LinkHelper::getYongoProjectComponentLink($component['project_component_id'], $component['name']);
                                        if ($indexComponents < $components->num_rows) echo ', ';
                                    ?>
                                    </span>
                            <?php $indexComponents++ ?>
                        <?php endwhile ?>
                    <?php else: ?>
                        <span>None</span>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="textLabel">Affects version/s:</div>
                </td>
                <td>
                    <?php $indexVersion = 1 ?>
                    <?php if ($versionsAffected): ?>
                        <?php while ($version = $versionsAffected->fetch_array(MYSQLI_ASSOC)): ?>
                            <span>
                                    <?php
                                        echo LinkHelper::getYongoProjectVersionLink($version['project_version_id'], $version['name']);
                                        if ($indexVersion < $versionsAffected->num_rows) echo ', ';
                                    ?>
                                </span>
                            <?php $indexVersion++ ?>
                        <?php endwhile ?>
                    <?php else: ?>
                        <span>None</span>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="textLabel">Fix Version/s:</div>
                </td>
                <td>
                    <?php $indexVersion = 1 ?>
                    <?php if ($versionsTargeted): ?>
                        <?php while ($version = $versionsTargeted->fetch_array(MYSQLI_ASSOC)): ?>
                            <span>
                                    <?php
                                        echo LinkHelper::getYongoProjectVersionLink($version['project_version_id'], $version['name']);
                                        if ($indexVersion < $versionsTargeted->num_rows) echo ', ';
                                    ?>
                                </span>
                            <?php $indexVersion++ ?>
                        <?php endwhile ?>
                    <?php else: ?>
                        <span>None</span>
                    <?php endif ?>
                </td>
            </tr>
            <tr>
                <td id="sectDescription" class="sectionDetail" colspan="2">
                    <span class="sectionDetailTitle headerPageTextSmall">Description</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="contentDescription"><?php echo str_replace("\n", '<br />', $issue['description']) ?></div>
                    <br />
                </td>
            </tr>
            <tr>
                <td id="sectDescription" class="sectionDetail" colspan="2">
                    <span class="sectionDetailTitle headerPageTextSmall">Environment</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="contentDescription"><?php echo str_replace("\n", '<br />', $issue['environment']) ?></div>
                    <br />
                </td>
            </tr>
            <tr>
                <td id="sectDescription" class="sectionDetail" colspan="2">
                    <span class="sectionDetailTitle headerPageTextSmall">People</span>
                </td>
            </tr>
            <tr>
                <td><div class="textLabel">Assignee:</div></td>
                <td>
                    <?php if ($issue[Field::FIELD_ASSIGNEE_CODE])
                        echo LinkHelper::getUserProfileLink($issue[Field::FIELD_ASSIGNEE_CODE], SystemProduct::SYS_PRODUCT_YONGO, $issue['ua_first_name'], $issue['ua_last_name']);
                    else
                        echo 'Unassigned'
                    ?>
                </td>
            </tr>
            <tr>
                <td><div class="textLabel">Reporter:</div></td>
                <td>
                    <?php echo LinkHelper::getUserProfileLink($issue[Field::FIELD_REPORTER_CODE], SystemProduct::SYS_PRODUCT_YONGO, $issue['ur_first_name'], $issue['ur_last_name']) ?>
                </td>
            </tr>
            <tr>
                <td id="sectDescription" class="sectionDetail" colspan="2">
                    <span class="sectionDetailTitle headerPageTextSmall">Dates</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="textLabel">Created:</div>
                </td>
                <td><?php if ($issue['date_created']) echo date('j M Y H:i', strtotime($issue['date_created'])); ?></td>
            </tr>
            <tr>
                <td>
                    <div class="textLabel">Updated:</div>
                </td>
                <td>
                    <?php if ($issue['date_updated'])
                        echo date('j M Y H:i', strtotime($issue['date_updated']));
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <div id="content_issue_agile_content_descr" style="display: none">
        <?php if ($issue['description']): ?>
            <?php echo $issue['description'] ?>
        <?php else: ?>
            <div>There is no description defined.</div>
        <?php endif ?>
    </div>

    <div id="content_issue_agile_content_comments" style="display: none">
        <?php
            $actionButtonsFlag = false;
            require_once __DIR__ . '/../../../Yongo/Resources/views/issue/comment/_comments.php'
        ?>
    </div>
    <div id="content_issue_agile_content_attachments" style="display: none">
        <?php if ($attachments): ?>
            <?php require_once __DIR__ . '/../../../Yongo/Resources/views/issue/_attachments.php' ?>
        <?php else: ?>
            <div>There are no attachments added. </div>
        <?php endif ?>
    </div>
    <div id="content_issue_agile_content_subtasks" style="display: none">
        <?php if ($childrenIssues): ?>
            <table>
                <?php $indexChildIssue = 1; ?>
                <?php while ($childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $indexChildIssue++ . '. <a href="/yongo/issue/' . $childIssue['id'] . '">' . $childIssue['summary'] ?></a></td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div>There are no sub-tasks defined.</div>
        <?php endif ?>
        <?php if (null == $issue['parent_id'] && $projectSubTaskIssueTypes): ?>
            <a href="#" id="agile_create_subtask_<?php echo $issueId ?>_<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Sub-Task</a>
        <?php endif ?>
    </div>
</div>