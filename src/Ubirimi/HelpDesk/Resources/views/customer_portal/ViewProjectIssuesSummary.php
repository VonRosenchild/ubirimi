<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>

    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Issues</div>
                </td>
            </tr>
        </table>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li <?php if ($menuProjectCategory == 'summary'): ?>class="active"<?php endif ?>>
                <a href="/helpdesk/customer-portal/project/<?php echo $projectId ?>" title="Summary">Summary</a>
            </li>
            <li <?php if ($menuProjectCategory == 'issues'): ?>class="active" <?php endif ?>>
                <a href="/helpdesk/customer-portal/project/issues/<?php echo $projectId ?>" title="Issues">Issues</a>
            </li>
        </ul>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top">
                    <table width="100%" cellpadding="2">
                        <tr>
                            <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Filters</span></td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('All Issues', array('page' => 1, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc')); ?>
                            </td>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Assigned to me', array('page' => 1, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'assignee' => $loggedInUserId)); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Unresolved', array('page' => 1, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'resolution' => -2)); ?>
                            </td>
                            <td>
                                <?php echo LinkHelper::getYongoIssueListPageLink('Reported by me', array('page' => 1, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'project' => $projectId, 'sort_order' => 'desc', 'reporter' => $loggedInUserId)); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top" width="50%">
                    <?php Util::renderComponentStatSection($stats_priority, $count, 'Unresolved by priority', 'priority', $projectId, null, null, '/helpdesk/customer-portal/tickets'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($stats_type, $count, 'Unresolved by type', 'type', $projectId, null, null, '/helpdesk/customer-portal/tickets'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="500px">
                    <?php Util::renderComponentStatSection($stats_status, $count, 'Status overview', 'status', $projectId, null, null, '/helpdesk/customer-portal/tickets'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($stats_assignee, $count, 'Unresolved by assignee', 'assignee', $projectId, null, null, '/helpdesk/customer-portal/tickets'); ?>
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
                                        echo LinkHelper::getYongoIssueListPageLink('No component', array('page' => 1, 'project' => $projectId, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'sort_order' => 'desc', 'component' => -1, 'resolution' => -2))
                                    ?>
                                </td>
                                <td width="30" align="right"><?php echo $countUnresolvedWithoutComponent ?></td>
                                <?php $perc = round($countUnresolvedWithoutComponent / $count * 100); ?>
                                <td valign="top">
                                    <div style="margin-top: 5px; margin-right: 4px; float:left; background-color: #56A5EC; height: 18px; width: <?php echo($perc * 4) ?>px"></div>
                                    <div style="float: left"><?php echo $perc ?>%</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No Issues</td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($stats_component as $key => $value): ?>
                            <tr>
                                <td width="180px">
                                    <?php
                                        echo LinkHelper::getYongoIssueListPageLink($key, array('page' => 1, 'project' => $projectId, 'link_to_page' => '/helpdesk/customer-portal/tickets', 'sort' => 'created', 'sort_order' => 'desc', 'component' => $value[0], 'resolution' => -2))
                                    ?>
                                </td>
                                <td width="30" align="right"><?php echo $value[1] ?></td>
                                <?php $perc = round($value[1] / $count * 100); ?>
                                <td valign="top">
                                    <div style="margin-top: 5px; margin-right: 4px; float:left; background-color: #56A5EC; height: 18px; width: <?php echo($perc * 4) ?>px"></div>
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

    <div id="modalProjectFilters"></div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>