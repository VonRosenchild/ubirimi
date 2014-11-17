<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Workflow Schemes') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Workflow Schemes') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/workflows">Workflows</a></li>
                <li><a href="/yongo/administration/workflows/issue-type-schemes">Workflow Issue Type Schemes</a></li>
                <li class="active"><a href="/yongo/administration/workflows/schemes">Workflow Schemes</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/workflows/add-scheme" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Workflow Scheme</a></td>
                    <td><a id="btnEditWorkflowScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnCopyWorkflowScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteWorkflowScheme" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
                </tr>
            </table>
            <div class="infoBox">
                <div>Workflow Schemes allow you to put together a collection of workflows that is going to be used in a project.</div>
            </div>
            <?php if ($workflowSchemes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Workflows</th>
                            <th>Projects</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scheme = $workflowSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $scheme['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $scheme['id'] ?>"/>
                                </td>
                                <td><?php echo $scheme['name']; ?></td>

                                <td width="400px">
                                    <ul>
                                        <?php $workflows = UbirimiContainer::get()['repository']->get(WorkflowScheme::class)->getDataById($scheme['id']) ?>
                                        <?php if ($workflows): ?>
                                            <?php while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)): ?>
                                                <li>
                                                    <a href="/yongo/administration/workflow/view-as-text/<?php echo $workflow['workflow_id'] ?>"><?php echo $workflow['name'] ?></a>
                                                </li>
                                            <?php endwhile ?>
                                        <?php endif ?>
                                    </ul>
                                </td>
                                <td width="300px">
                                    <?php $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByWorkflowSchemeId($scheme['id']) ?>
                                    <?php if ($projects): ?>
                                        <ul>
                                            <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                                <li><a href="/yongo/administration/project/<?php echo $project['id'] ?>"><?php echo $project['name'] ?></a></li>
                                            <?php endwhile ?>
                                        </ul>
                                        <input type="hidden" id="delete_possible_<?php echo $scheme['id'] ?>" value="0"/>
                                    <?php else: ?>
                                        <input type="hidden" id="delete_possible_<?php echo $scheme['id'] ?>" value="1"/>
                                    <?php endif ?>
                                </td>
                                <td>
                                    <?php if ($projects): ?>
                                        <div>Active</div>
                                    <?php else: ?>
                                        <div>Inactive</div>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no workflow schemes defined</div>
            <?php endif ?>
        </div>
        <div class="ubirimiModalDialog" id="modalDeleteWorkflowScheme"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php endif ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>