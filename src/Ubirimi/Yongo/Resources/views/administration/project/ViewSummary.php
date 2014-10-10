<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Summary</div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/_main_actions.php' ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/project/<?php echo $projectId ?>">Summary</a></li>
            <li><a href="/yongo/administration/project/issue-types/<?php echo $projectId ?>">Issue Types</a></li>
            <li><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Workflows</a></li>
            <li><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <div class="separationVertical"></div>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" width="50%">
                    <?php require_once __DIR__ . '/_sectionIssueTypes.php' ?>
                    <?php require_once __DIR__ . '/_sectionWorkflows.php' ?>
                    <?php require_once __DIR__ . '/_sectionScreens.php' ?>
                    <?php require_once __DIR__ . '/_sectionFields.php' ?>
                </td>
                <td width="10px"></td>
                <td valign="top">
                    <?php require_once __DIR__ . '/_sectionPeople.php' ?>
                    <?php require_once __DIR__ . '/_sectionVersions.php' ?>
                    <?php require_once __DIR__ . '/_sectionComponents.php' ?>
                    <?php require_once __DIR__ . '/_sectionPermissions.php' ?>
                    <?php require_once __DIR__ . '/_sectionNotifications.php' ?>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>