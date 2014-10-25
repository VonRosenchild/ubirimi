<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <div class="noPrint">
        <div style="margin: 10px;" class="noPrint">
            <a class="noPrint btn ubirimi-btn" href="/yongo/issue/search?<?php echo $parseURLData['query'] ?>">Back to previous view</a>
        </div>
    </div>

    <div class="pageContent">
        <?php if (isset($issuesCount) && $issuesCount > 0): ?>
            <?php
            $htmlOutputIssueRow = '';
            $arrayIds = array();
            while ($issue = $issues->fetch_array(MYSQLI_ASSOC)) {
                $securityLevelId = $issue['security_level'];

                $arrayIds[] = $issue['id'];
                $htmlOutputIssueRow .= '<tr id="table_row_' . $issue['id'] . '">';

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
                        if ($issue['date_updated']) {
                            $htmlOutputIssueRow .= date('j/M/Y', strtotime($issue['date_updated']));
                        }
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
        <?php else: ?>
            <div>
                <table class="table table-hover table-condensed">
                    <td colspan="<?php echo count($columns) ?>">No issues were found to match your search</td>
                </table>
            </div>
        <?php endif ?>
    </div>
</body>