<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;

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
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Issue Security > <?php if ($issueSecurityScheme)
                            echo $issueSecurityScheme['name']; else echo 'Anyone' ?></div>
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
            <li><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li class="active"><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($issueSecurityScheme): ?>
                    <td><a href="/yongo/administration/issue-security-scheme-levels/<?php echo $issueSecurityScheme['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Issue Security</a></td>
                    <td><a href="/yongo/administration/project/associate-issue-security-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                <?php else: ?>
                    <td><a href="/yongo/administration/project/associate-issue-security-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Select a scheme</a></td>
                <?php endif ?>
            </tr>
        </table>

        <div class="infoBox">
            Issue Security allows you to control who can and cannot view issues. They consist of a number of security levels which can have users/groups assigned to them. The issue security scheme defines how the securities are configured for this project. To change the securities, you can select a different issue secuirty scheme, or modify the currently selected scheme.
        </div>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Security Level</th>
                    <th>Description</th>
                    <th width="40%">Users / Groups / Project Roles</th>
                </tr>
            </thead>
            <?php if ($issueSecurityScheme): ?>
                <?php $issueSecuritySchemeLevels = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($issueSecurityScheme['id']); ?>
                <tbody>
                    <?php while ($level = $issueSecuritySchemeLevels->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <div><?php echo $level['name'] ?></div>
                            </td>
                            <td>
                                <div><?php echo $level['description'] ?></div>
                            </td>
                            <td>
                                <?php
                                    $notificationData = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getDataByLevelId($level['id']);
                                    if ($notificationData) {
                                        echo '<ul>';
                                        while ($data = $notificationData->fetch_array(MYSQLI_ASSOC)) {
                                            if ($data['current_assignee']) {
                                                echo '<li>Current Assignee</li>';
                                            } else if ($data['reporter']) {
                                                echo '<li>Reporter</li>';
                                            } else if ($data['project_lead']) {
                                                echo '<li>Project Lead</li>';
                                            } else if ($data['first_name']) {
                                                echo '<li>Single User (' . $data['first_name'] . ' ' . $data['last_name'] . ')</li>';
                                            } else if ($data['group_name']) {
                                                echo '<li> Group (' . $data['group_name'] . ')</li>';
                                            } else if ($data['role_name']) {
                                                echo '<li>Project Role (' . $data['role_name'] . ')</li>';
                                            }
                                        }
                                        echo '</ul>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            <?php else: ?>
                <tr>
                    <td colspan="3">Issue security is currently not enabled for this project.</td>
                </tr>
            <?php endif ?>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>