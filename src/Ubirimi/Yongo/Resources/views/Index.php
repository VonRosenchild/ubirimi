<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

require_once __DIR__ . '/_header.php';
$section = 'dashboard';
?>

<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Home > My Dashboard') ?>

    <div class="pageContent">

        <?php require_once __DIR__ . '/_home_subtabs.php' ?>
        <div style="padding-top: 4px; padding-bottom: 4px"></div>
        <?php if ($allProjects == null && ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission)): ?>
            <div class="infoBox" style="margin-top: 4px;">
                <div>There are no projects created. You can create one by clicking <a class="linkNoUnderline" href="/yongo/administration/project/add"><b>here</b></a>.</div>
            </div>
        <?php endif ?>

        <table width="100%" cellpadding="0px" cellspacing="0">
            <tr>
                <td width="49%" valign="top">
                    <ul class="nav nav-tabs" style="padding: 0px;">
                        <li class="active"><a href="#" title="Created vs. Resolved Chart">Created vs. Resolved Chart</a></li>
                    </ul>

                    <div style="border: 1px solid #d6d6d6; border-top: none; padding: 8px">
                        <?php if (count($projectIdsNames)): ?>
                            <span>Select Project</span>
                            <select name="chart_project" id="chart_project_created_resolved" class="select2InputMedium">
                                <option value="-1" selected="selected">All Projects</option>
                                <?php for ($i = 0; $i < count($projectIdsNames); $i++): ?>
                                    <option value="<?php echo $projectIdsNames[$i][0] ?>"><?php echo $projectIdsNames[$i][1] ?></option>
                                <?php endfor ?>
                            </select>

                            <div id="chart_created_resolved" style="height: 300px"></div>
                        <?php else: ?>
                            <div>There are no projects to display the chart for.</div>
                        <?php endif ?>
                    </div>

                    <br />

                    <ul class="nav nav-tabs" style="padding: 0px;">
                        <li class="active"><a href="#" title="Issues assigned to me">Assigned to Me (Unresolved)</a></li>
                    </ul>

                    <div style="max-height: 700px; overflow: auto;">
                        <div style="border: 1px solid #d6d6d6; border-top: none;">
                            <?php
                                if ($issues) {

                                    $renderParameters = array('issues' => $issues, 'render_checkbox' => false, 'show_header' => true);
                                    $renderColumns = array('code', 'summary', 'priority');
                                    $issuesRendered = Util::renderIssueTables($renderParameters, $renderColumns, $clientSettings);
                                    if (!$issuesRendered) {
                                        echo '<div style="padding: 8px;">There are no unresolved issues assigned to you.</div>';
                                    }
                                } else {
                                    echo '<div style="padding: 8px;">There are no unresolved issues assigned to you.</div>';
                                }
                            ?>
                        </div>
                    </div>
                </td>
                <td width="2%"></td>
                <td width="49%" valign="top">

                    <ul class="nav nav-tabs" style="padding: 0px;">
                        <li class="active"><a href="#" title="Unresolved Issues">Unresolved Issues (Others)</a></li>
                    </ul>

                    <div style="max-height: 500px; overflow: auto;">
                        <div style="border: 1px solid #d6d6d6; border-top: none;">
                            <?php require_once __DIR__ . '/charts/ViewUnresolvedOthers.php' ?>
                        </div>
                    </div>

                    <br />
                    <?php if (UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS) || UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS)): ?>
                        <ul class="nav nav-tabs" style="padding: 0px;">
                            <li class="active"><a href="#" title="Administration">Administration</a></li>
                        </ul>
                        <div style="border: 1px solid #d6d6d6; border-top: none; padding: 8px;">
                            <table>
                                <tr>
                                    <td><b>Projects</b></td>
                                    <td><a href="/yongo/administration/projects">View All</a> or <a href="/yongo/administration/project/add">Create New</a></td>
                                </tr>
                                <tr>
                                    <td><b>Users</b></td>
                                    <td>Browse <a href="/yongo/administration/users">users</a>, <a href="/yongo/administration/groups">groups</a> or <a href="/general-settings/users/add">create new user</a></td>
                                </tr>
                            </table>
                        </div>
                    <?php endif ?>
                </td>
            </tr>
        </table>

    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>