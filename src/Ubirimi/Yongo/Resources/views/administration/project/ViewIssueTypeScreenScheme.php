<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScreenScheme;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Screens</div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/_main_actions.php' ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/project/<?php echo $projectId ?>">Summary</a></li>
            <li><a href="/yongo/administration/project/issue-types/<?php echo $projectId ?>">Issue Types</a></li>
            <li><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Workflows</a></li>
            <li class="active"><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/project/select-issue-type-screen-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                </tr>
            </table>
        <?php endif ?>

        <?php while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
        <table width="100%">
            <tr>
                <td colspan="2"><div class="headerPageText"><?php echo $screenScheme['name'] ?></div></td>
            </tr>
            <tr>
                <td valign="top" width="180">
                    <?php $issueTypes = UbirimiContainer::get()['repository']->get(IssueTypeScreenScheme::class)->getIssueTypesForScreenScheme($project['issue_type_screen_scheme_id'], $screenScheme['id']); ?>
                    <div><b>These <?php echo $issueTypes->num_rows ?> issue types...</b></div>
                    <?php while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                        <div><?php echo $issueType['name'] ?></div>
                    <?php endwhile ?>
                </td>
                <td valign="top">
                    <?php $screenSchemeData = UbirimiContainer::get()['repository']->get(ScreenScheme::class)->getDataByScreenSchemeId($screenScheme['id']); ?>
                    <div><b>... use this screen scheme</b></div>
                    <table class="table table-hover table-condensed">
                        <tr>
                            <td width="200">Issue Operation</td>
                            <th>Screen</th>
                        </tr>

                        <?php while ($data = $screenSchemeData->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr>
                                <td><?php echo ucfirst($data['operation_name']) . ' Issue'; ?></td>
                                <td><a href="/yongo/administration/screen/configure/<?php echo $data['screen_id'] ?>?source=project_screen&project_id=<?php echo $project['id'] ?>"><?php echo $data['screen_name']; ?></a></td>
                            </tr>
                        <?php endwhile ?>
                    </table>
                </td>
            </tr>
        </table>
        <?php endwhile ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>