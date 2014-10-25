<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;
use Ubirimi\Yongo\Repository\Screen\Screen;

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
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Fields</div>
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
            <li class="active"><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
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
                    <td><a href="/yongo/administration/project/select-issue-type-field-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                </tr>
            </table>
        <?php endif ?>

        <?php while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)): ?>
            <table width="100%">
                <tr>
                    <td colspan="2"><div class="headerPageText"><?php echo $fieldConfiguration['name'] ?></div></td>
                </tr>
                <tr>
                    <td valign="top" width="180">
                        <?php $issueTypes = UbirimiContainer::get()['repository']->get(FieldConfigurationScheme::class)->getIssueTypesForFieldConfiguration($project['issue_type_field_configuration_id'], $fieldConfiguration['id']); ?>
                        <div><b>These <?php echo $issueTypes->num_rows ?> issue types...</b></div>
                        <?php while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><?php echo $issueType['name'] ?></div>
                        <?php endwhile ?>
                    </td>
                    <td valign="top">
                        <div><b>...use this field configuration</b></div>
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th>Name</th>
                                <th align="left">Screens</th>
                            </tr>

                            <?php while ($field = $allFields->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr>
                                <td><?php echo $field['name'] ?></td>
                                <td align="left">

                                    <?php $screens = UbirimiContainer::get()['repository']->get(Screen::class)->getByFieldId($clientId, $field['id']) ?>
                                    <?php if ($screens): ?>
                                    <ul>
                                        <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                                            <li><a href="/yongo/administration/screen/configure/<?php echo $screen['id'] ?>?source=project_field&project_id=<?php echo $project['id'] ?>"><?php echo $screen['name'] ?></a></li>
                                        <?php endwhile ?>
                                    </ul>
                                    <?php endif ?>
                                </td>
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