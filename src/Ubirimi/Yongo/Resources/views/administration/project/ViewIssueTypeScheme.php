<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Screen\ScreenScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

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
                    <div class="headerPageText"><a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > <?php echo $project['name'] ?> > <?php echo $issueTypeDefaultScheme['name'] ?></div>
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
            <li class="active"><a href="/yongo/administration/project/issue-types/<?php echo $projectId ?>">Issue Types</a></li>
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

        <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a href="/yongo/administration/issue/edit-type-scheme/<?php echo $issueTypeDefaultScheme['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Issue Types</a></td>
                    <!--                <td><a id="btnEditPermRole" href="#" class="btn ubirimi-btn disabled">Use a Different Scheme</a></td>-->
                </tr>
            </table>
        <?php endif ?>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Issue Type</th>
                    <th>Description</th>
                    <th>Workflow</th>
                    <th>Field Configuration</th>
                    <th>Screen</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($data = $issueTypeDefaultSchemeData->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $data['name'] ?></td>
                        <td><?php echo $data['description'] ?></td>
                        <td>
                            <?php
                                $workflows = UbirimiContainer::get()['repository']->get(Workflow::class)->getByIssueType($data['issue_type_id'], $clientId);
                                if ($workflows) {
                                    echo '<ul>';
                                    while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<li><a href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                $fieldConfigurations = UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->getByIssueType($data['issue_type_id'], $clientId);
                                if ($fieldConfigurations) {
                                    echo '<ul>';
                                    while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<li><a href="/yongo/administration/field-configuration/edit/' . $fieldConfiguration['id'] . '">' . $fieldConfiguration['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                $screenSchemes = UbirimiContainer::get()['repository']->get(ScreenScheme::class)->getByIssueType($data['issue_type_id'], $clientId);
                                if ($screenSchemes) {
                                    echo '<ul>';
                                    while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)) {
                                        echo '<li><a href="/yongo/administration/screen/configure-scheme/' . $screenScheme['id'] . '">' . $screenScheme['name'] . '</a></li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>