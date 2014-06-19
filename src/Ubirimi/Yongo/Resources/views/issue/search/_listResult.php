<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;

    $arrayIds = array();
    $selectedProductId = $session->get('selected_product_id');
?>
    <div class="pageContent" style="margin: 0px; border-radius: 0px;">
    <?php if (isset($issuesCount) && $issuesCount > 0): ?>

        <?php Util::renderPaginator($getSearchParameters); ?>

        <table class="table table-hover table-condensed">
            <?php echo Util::renderTableHeader($getSearchParameters, $columns); ?>
            <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                <?php $arrayIds[] = $issue['id']; ?>
                <tr>
                    <?php for ($i = 0; $i < count($columns); $i++): ?>
                        <?php if ($columns[$i] == 'code'): ?>
                            <td class="issuePC">
                                <a href="<?php echo $urlIssuePrefix . $issue['id'] ?>"><?php echo $issue['project_code'] . '-' . $issue['nr'] ?></a>
                            </td>
                        <?php endif ?>
                        <?php if ($columns[$i] == 'type'): ?>
                            <td class="issueType">
                                <img title="<?php echo $issue['type_name'] . ' - ' . $issue['issue_type_description'] ?>"
                                     height="16px"
                                     src="/yongo/img/issue_type/<?php echo $issue['issue_type_icon_name'] ?>" />
                            </td>
                        <?php endif ?>
                        <?php if ($columns[$i] == 'summary'): ?>
                                <td class="issueSummary">
                                <a href="<?php echo $urlIssuePrefix . $issue['id'] ?>"><?php echo $issue['summary'] ?></a>
                            </td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'description'): ?>
                            <td class="issueDe">
                                <a href="/yongo/issue/<?php echo $issue['id'] ?>"><?php echo $issue['description'] ?></a>
                            </td>
                        <?php endif ?>
                        <?php if ($columns[$i] == 'priority'): ?>
                            <td class="issuePriority">
                                <img title="<?php echo $issue['priority_name'] . ' - ' . $issue['issue_priority_description'] ?>"
                                     height="16px"
                                     src="/yongo/img/issue_priority/<?php echo $issue['issue_priority_icon_name'] ?>" />
                            </td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'status'): ?>
                            <td class="issueStatus"><?php echo $issue['status_name'] ?></td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'created'): ?>
                            <td class="issueDC">
                                <?php echo Util::getFormattedDate($issue['date_created']) ?>
                            </td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'updated'): ?>
                            <td class="issueDU">
                                <?php if ($issue['date_updated']): ?>
                                    <?php echo Util::getFormattedDate($issue['date_updated']) ?>
                                <?php endif ?>
                            </td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'reporter'): ?>
                            <td class="issueUR">
                                <?php echo LinkHelper::getUserProfileLink($issue[Field::FIELD_REPORTER_CODE], $selectedProductId, $issue['ur_first_name'], $issue['ur_last_name']) ?>
                            </td>
                        <?php endif ?>
                        <?php if ($columns[$i] == 'assignee'): ?>
                            <td class="issueUA">
                                <?php echo LinkHelper::getUserProfileLink($issue[Field::FIELD_ASSIGNEE_CODE], $selectedProductId, $issue['ua_first_name'], $issue['ua_last_name']) ?>
                            </td>
                        <?php endif ?>
                        <?php if (substr($columns[$i], 0, 4) == 'sla_'): ?>
                            <?php
                                $slaId = str_replace('sla_', '', $columns[$i]);

                                // check to see of the SLA is applicable to the issue project
                                $applicable = SLA::checkSLABelongsToProject($slaId, $issue['issue_project_id']);
                                $offset = '';
                                if ($applicable) {
                                    $SLA = SLA::getById($slaId);
                                    $slaData = SLA::getOffsetForIssue($SLA, $issue, $clientId, $clientSettings);
                                    $offset = $slaData[0];
                                }
                            ?>
                            <td class="issueSLA">
                                <?php if (isset($offset)): ?>
                                    <span class="<?php if ($offset < 0) echo 'slaNegative'; else echo 'slaPositive' ?>">
                                        <?php echo SLA::formatOffset($offset) ?>
                                    </span>
                                    &nbsp;
                                    <img src="/img/clock.png" height="16px" />
                                <?php endif ?>
                            </td>
                        <?php endif ?>
                        <?php if (Util::checkUserIsLoggedIn() && $columns[$i] == 'settings_menu'):  ?>
                            <td width="20px" align="center">
                                <img id="issue_search_<?php echo $issue['id'] ?>" width="20px" src="/img/settings.png" />
                            </td>
                        <?php endif ?>
                    <?php endfor ?>
                </tr>
            <?php endwhile ?>
            <?php $session->set('array_ids', $arrayIds) ?>

            <?php Util::renderPaginator($getSearchParameters); ?>
        </table>
    <?php else: ?>
        <table>
            <tbody>
                <tr>
                    <td colspan="<?php echo count($columns) ?>">No issues were found to match your search</td>
                </tr>
            </tbody>
        </table>
    <?php endif ?>
</div>