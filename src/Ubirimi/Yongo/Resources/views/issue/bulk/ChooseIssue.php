<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Bulk Operation Step 1 of 4 > Choose Issues'); ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/issue/bulk-choose?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>">Choose Issues</a></li>
            <li class="disabled"><a>Choose Operation</a></li>
            <li class="disabled"><a>Operation Details</a></li>
            <li class="disabled"><a>Confirmation</a></li>
        </ul>

        <form action="/yongo/issue/bulk-choose?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" method="post" name="bulk_operation">
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="next_step_2">Next Step</button>
                        <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                    </td>
                </tr>
            </table>

            <?php if ($errorNoIssuesSelected): ?>
            <div class="errorWithBackground">You must select at least one issue to bulk edit.</div>
            <div style="height: 6px"></div>
            <?php endif ?>

            <?php if (isset($issuesCount) && $issuesCount > 0): ?>
                <?php
                    $htmlOutputIssueRow = '';
                    $arrayIds = array();
                    while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                        $securityLevelId = $issue['security_level'];

                        $arrayIds[] = $issue['id'];
                        $htmlOutputIssueRow .= '<tr id="table_row_' . $issue['id'] . '">';
                            $htmlOutputIssueRow .= '<td class="tdElementList" align="center"><input type="checkbox" value="1" name="issue_checkbox_' . $issue['id'] . '" id="el_check_' . $issue['id'] . '" /></td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issuePC">';
                                $htmlOutputIssueRow .= '<img title="' . $issue['type_name'] . ' - ' . $issue['issue_type_description'] . '" height="16px" src="/yongo/img/issue_type/' . $issue['issue_type_icon_name'] . '" /> ';
                                $htmlOutputIssueRow .= '<a href="/yongo/issue/' . $issue['id'] . '">' . $issue['project_code'] . '-' . $issue['nr'] . '</a>';
                                $htmlOutputIssueRow .= '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueSummary"><a href="/yongo/issue/' . $issue['id'] . '">' . $issue['summary'] . '</a></td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issuePriority">';
                                $htmlOutputIssueRow .= '<img title="' . $issue['priority_name'] . ' - ' . $issue['issue_priority_description'] . '" height="16px" src="/yongo/img/issue_priority/' . $issue['issue_priority_icon_name'] . '" />';
                                $htmlOutputIssueRow .= '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueStatus">' . $issue['status_name'] . '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueDC">' . date('j/M/Y', strtotime($issue['date_created'])) . '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueDU">';
                                if ($issue['date_updated'])
                                $htmlOutputIssueRow .= date('j/M/Y', strtotime($issue['date_updated']));
                                $htmlOutputIssueRow .= '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueUR">' . LinkHelper::getUserProfileLink($issue[Field::FIELD_REPORTER_CODE], SystemProduct::SYS_PRODUCT_YONGO, $issue['ur_first_name'], $issue['ur_last_name']) . '</td>';
                            $htmlOutputIssueRow .= '<td class="tdElementList issueUA">' . LinkHelper::getUserProfileLink($issue[Field::FIELD_ASSIGNEE_CODE], SystemProduct::SYS_PRODUCT_YONGO, $issue['ua_first_name'], $issue['ua_last_name']) . '</td>';
                        $htmlOutputIssueRow .= '</tr>';
                    }
                    $session->set('array_ids', $arrayIds);
                    if ($htmlOutputIssueRow != '') {

                        echo '<table class="table table-hover table-condensed">';
                            echo Util::renderTableHeader($getSearchParameters, $columns);
                            echo $htmlOutputIssueRow;
                        echo '</table>';
                    } else {
                        $htmlOutputIssueRow .= '<table class="table table-hover table-condensed">';
                            $htmlOutputIssueRow .= '<tr id="table_row_' . $issue['id'] . '">';
                                $htmlOutputIssueRow .= '<td colspan="' . count($columns) . '">No issues were found to match your search</td>';
                                $htmlOutputIssueRow .= '</tr>';
                            $htmlOutputIssueRow .= '</table>';
                        echo $htmlOutputIssueRow;
                    }
                ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td>
                            <button type="submit" class="btn ubirimi-btn" style="margin-left: 0px" name="next_step_2">Next Step</button>
                            <a href="/yongo/issue/search?<?php echo $session->get('bulk_change_choose_issue_query_url') ?>" class="btn ubirimi-btn">Cancel</a>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <div>
                    <table class="table table-hover table-condensed">
                        <td colspan="<?php echo count($columns) ?>">No issues were found to match your search</td>
                    </table>
                </div>
            <?php endif ?>
        </form>
    </div>
</body>