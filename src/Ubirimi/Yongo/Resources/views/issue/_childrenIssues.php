<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Yongo\Repository\Field\Field;

?>
<tr>
    <td id="sectSubTasks" class="sectionDetail" colspan="2">
        <span class="sectionDetailTitle headerPageText">Sub-Tasks</span>
        <div style="height: 10px"></div>
    </td>
</tr>
<tr>
    <td>
        <table width="100%" class="table table-hover table-condensed" id="contentSubTasks">
            <?php $indexChildIssue = 1; ?>
            <tbody>
                <?php while ($childIssue = $childrenIssues->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td width="60%">
                            <?php
                                echo $indexChildIssue++ . '. ';
                                if ($childIssue['resolution']) {
                                    echo '<img style="vertical-align: middle; padding-bottom: 2px" src="/img/accept.png" />';
                                }
                                echo ' <a href="/yongo/issue/' . $childIssue['id'] . '">' . $childIssue['summary'] . '</a>';
                            ?>
                        </td>
                        <td>
                            <img title="<?php echo $issue['type_name'] . ' - ' . $childIssue['issue_type_description'] ?>"
                                 height="16px"
                                 src="/yongo/img/issue_type/<?php echo $childIssue['issue_type_icon_name'] ?>" />
                        </td>
                        <td>
                            <img title="<?php echo $issue['priority_name'] . ' - ' . $childIssue['issue_priority_description'] ?>"
                                 height="16px"
                                 src="/yongo/img/issue_priority/<?php echo $childIssue['issue_priority_icon_name'] ?>" />
                        </td>
                        <td style="width: 150px;">
                            <?php echo $childIssue['status_name'] ?>
                        </td>
                        <td style="text-align: right; width: 200px;">
                            <?php echo LinkHelper::getUserProfileLink($issue[Field::FIELD_ASSIGNEE_CODE], SystemProduct::SYS_PRODUCT_YONGO, $childIssue['ua_first_name'], $childIssue['ua_last_name']) ?>
                        </td>
                        <td align="right" style="text-align: right;">
                            <div class="btn-group" align="right">
                                <a id="btnOptions" href="#" class="btn ubirimi-btn dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-asterisk"></i> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu pull-right">
                                    <li><a id="edit_issue_child_<?php echo $childIssue['id'] ?>" href="#">Edit</a></li>
                                    <?php if ($childIssue['assignee'] != $loggedInUserId): ?>
                                        <li><a id="agile_plan_assign_to_me_<?php echo $childIssue['id'] ?>_1" href="#">Assign to Me</a></li>
                                    <?php endif ?>
                                    <li><a id="agile_plan_assign_other_<?php echo $childIssue['id'] ?>_<?php echo $childIssue['issue_project_id'] ?>_<?php echo $childIssue['assignee'] ?>_1" href="#">Assign</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </td>
</tr>
