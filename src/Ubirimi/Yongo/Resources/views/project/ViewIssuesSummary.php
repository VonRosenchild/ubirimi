<?php
use Ubirimi\LinkHelper;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Issues</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <?php
            $menuProjectCategory = 'issues';
            require_once __DIR__ . '/_summaryMenu.php';
        ?>

        <?php if ($hasAdministerProject): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <a id="btnNew" href="/yongo/administration/project/<?php echo $projectId ?>" class="btn ubirimi-btn">Administer Project</a>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div class="separationVertical"></div>
        <?php endif ?>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top">
                    <table width="100%" cellpadding="2">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Filters</span></td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('All Issues', array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc')); ?>
                            </td>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Assigned to me', array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'assignee' => $loggedInUserId)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Unresolved', array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'resolution' => -2)); ?>
                            </td>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Reported by me', array('page' => 1, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'reporter' => $loggedInUserId)); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top" width="50%">
                    <?php Util::renderComponentStatSection($statsPriority, $count, 'Unresolved by priority', 'priority', $projectId, null, null, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsType, $count, 'Unresolved by type', 'type', $projectId, null, null, '/yongo/issue/search'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="500px">
                    <?php Util::renderComponentStatSection($statsStatus, $count, 'Status overview', 'status', $projectId, null, null, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsAssignee, $count, 'Unresolved by assignee', 'assignee', $projectId, null, null, '/yongo/issue/search'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="50%">
                    <table width="100%" cellpadding="2">
                        <tr>
                            <td colspan="3" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Unresolved by component</span></td>
                        </tr>
                        <?php if ($countUnresolvedWithoutComponent): ?>
                            <tr>
                                <td width="180px">
                                    <?php
                                        echo LinkHelper::getYongoIssueListPageLink('No component', array('page' => 1, 'project' => $projectId, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'sort_order' => 'desc', 'component' => -1, 'resolution' => -2))
                                    ?>
                                </td>
                                <td width="30" align="right"><?php echo $countUnresolvedWithoutComponent ?></td>
                                <?php $perc = round($countUnresolvedWithoutComponent / $count * 100); ?>
                                <td valign="top">
                                    <div style="margin-top: 5px; margin-right: 4px; float:left; background-color: #56A5EC; height: 18px; width: <?php echo($perc * 3) ?>px"></div>
                                    <div style="float: left"><?php echo $perc ?>%</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No Issues</td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($statsComponent as $key => $value): ?>
                            <tr>
                                <td width="180px">
                                    <?php
                                        echo LinkHelper::getYongoIssueListPageLink($key, array('page' => 1, 'project' => $projectId, 'link_to_page' => '/yongo/issue/search', 'sort' => 'created', 'sort_order' => 'desc', 'component' => $value[0], 'resolution' => -2))
                                    ?>
                                </td>
                                <td width="30" align="right"><?php echo $value[1] ?></td>
                                <?php $perc = round($value[1] / $count * 100); ?>
                                <td valign="top">
                                    <div style="margin-top: 5px; margin-right: 4px; float:left; background-color: #56A5EC; height: 18px; width: <?php echo($perc * 3) ?>px"></div>
                                    <div style="float: left"><?php echo $perc ?>%</div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <div class="ubirimiModalDialog" id="modalProjectFilters"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>