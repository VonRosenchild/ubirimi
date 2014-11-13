<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\LinkHelper;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\Issue;

$arrayIds = array();
?>
<div class="pageContent" style="border-bottom: 0; margin: 0; border-radius: 0;">
    <?php if (isset($issuesCount) && $issuesCount > 0): ?>

        <?php if ($cliMode == false): ?>
            <?php Util::renderPaginator($issuesCount, $issuesPerPage, $currentSearchPage, $getSearchParameters); ?>
        <?php endif ?>
        <table class="table table-hover table-condensed">
            <?php echo Util::renderTableHeader($getSearchParameters, $columns); ?>
            <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                <?php
                    $arrayIds[] = $issue['id'];

                    $slaData = UbirimiContainer::get()['repository']->get(Issue::class)->updateSLAValue($issue, $clientId, $clientSettings);
                ?>
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
                                <?php echo Util::getFormattedDate($issue['date_created'], $clientSettings['timezone']) ?>
                            </td>
                        <?php endif ?>

                        <?php if ($columns[$i] == 'updated'): ?>
                            <td class="issueDU">
                                <?php if ($issue['date_updated']): ?>
                                    <?php echo Util::getFormattedDate($issue['date_updated'], $clientSettings['timezone']) ?>
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
                                $slaIds = explode("_", str_replace('sla_', '', $columns[$i]));
                            ?>
                            <td class="issueSLA">
                                <?php for ($j = 0; $j < count($slaIds); $j++): ?>
                                    <?php $slaId = $slaIds[$j]; ?>
                                    <?php for ($k = 0; $k < count($slaData); $k++): ?>
                                        <?php if (isset($slaData[$k]) && $slaData[$k]['slaId'] == $slaIds[$j]): ?>
                                            <span class="<?php if (($slaData[$k]['goalValue'] - $slaData[$k]['intervalMinutes']) < 0) echo 'slaNegative'; else echo 'slaPositive' ?>">
                                                <?php echo UbirimiContainer::get()['repository']->get(Sla::class)->formatOffset($slaData[$k]['goalValue'] - $slaData[$k]['intervalMinutes']) ?>
                                            </span>
                                            <?php if ($slaData[$k]['endDate']): ?>
                                                <img src="/img/accept.png" style="position: relative; top: 3px;" />
                                            <?php else: ?>
                                                <img src="/img/clock.png" height="16px" style="position: relative; top: 3px;"/>
                                            <?php endif ?>
                                        <?php endif ?>
                                    <?php endfor ?>
                                <?php endfor ?>
                            </td>
                        <?php endif ?>
                        <?php if ($columns[$i] == 'settings_menu' && Util::checkUserIsLoggedIn()):  ?>
                            <td width="20px" align="center">
                                <img id="issue_search_<?php echo $issue['id'] ?>" width="20px" src="/img/settings.png" />
                            </td>
                        <?php endif ?>
                    <?php endfor ?>
                </tr>
            <?php endwhile ?>
            <?php if ($cliMode == false): ?>
                <?php $session->set('array_ids', $arrayIds); ?>
                <?php Util::renderPaginator($issuesCount, $issuesPerPage, $currentSearchPage, $getSearchParameters); ?>
            <?php endif ?>
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