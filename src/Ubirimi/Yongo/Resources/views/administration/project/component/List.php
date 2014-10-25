<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/projects">Projects</a> > <?php echo $project['name'] ?> > Components
                    </div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/../_main_actions.php' ?>
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
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li class="active"><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="" href="/yongo/administration/project/component/add/<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create component</a></td>
                <?php if ($components): ?>
                    <td><a id="btnCreateSubcomponent" href="#" class="btn ubirimi-btn disabled">Create Sub-Component</a></td>
                    <td><a id="btnEditComponent" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteProjectComponent" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>

        <?php if ($components): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Leader</th>
                    </tr>
                </thead>
                <tbody>
                    <?php UbirimiContainer::get()['repository']->get(YongoProject::class)->renderTreeComponents($components, 0) ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteProjectComponent"></div>
        <?php else: ?>
            <div class="messageGreen">There are no components defined for this project.</div>
        <?php endif ?>
    </div>
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>