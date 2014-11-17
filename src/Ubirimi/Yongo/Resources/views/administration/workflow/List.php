<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;
use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Workflows', 'help', 'https://support.ubirimi.net/documentador/page/view/43') ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active"><a href="/yongo/administration/workflows">Workflows</a></li>
                <li><a href="/yongo/administration/workflows/issue-type-schemes">Workflow Issue Type Schemes</a></li>
                <li><a href="/yongo/administration/workflows/schemes">Workflow Schemes</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <a id="btnNewProjectWorkflow" href="/yongo/administration/workflow/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Workflow</a>
                        <a id="btnEditWorkflow" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a>
                        <a id="btnDesignWorkflow" href="#" class="btn ubirimi-btn disabled">Design</a>
                        <a id="btnWorkflowViewAsText" href="#" class="btn ubirimi-btn disabled">View as Text</a>
                        <a id="btnDeleteWorkflow" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a>
                        <a id="btnCopyWorkflow" href="#" class="btn ubirimi-btn disabled">Copy</a>
                    </td>
                </tr>
            </table>
            <?php if ($workflows): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Workflow Issue Type Scheme</th>
                        <th>Assigned Workflow Schemes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $workflow['id'] ?>">
                        <td width="22">
                            <div>
                                <input type="checkbox" value="1" id="el_check_<?php echo $workflow['id'] ?>"/>
                            </div>

                        </td>
                        <td>
                            <div>
                                <a href="/yongo/administration/workflow/view-as-text/<?php echo $workflow['id'] ?>"><?php echo $workflow['name'] ?></a>
                            </div>
                            <div class="smallDescription"><?php echo $workflow['description'] ?></div>
                        </td>
                        <td>
                            <?php echo $workflow['issue_type_scheme_name'] ?>
                        </td>
                        <td width="400px">
                            <?php $workflowSchemes = UbirimiContainer::get()['repository']->get(WorkflowScheme::class)->getByWorkflowId($workflow['id']) ?>
                            <?php if ($workflowSchemes): ?>
                            <ul>
                                <?php while ($workflowScheme = $workflowSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                    <li><?php echo $workflowScheme['name'] ?></li>
                                <?php endwhile ?>
                            </ul>
                            <input type="hidden" id="delete_possible_<?php echo $workflow['id'] ?>" value="0">
                            <?php else: ?>
                                <input type="hidden" id="delete_possible_<?php echo $workflow['id'] ?>" value="1">
                            <?php endif ?>
                        </td>
                        <td>
                            <?php
                                $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByWorkflowId($workflow['id']);
                                if ($projects) {
                                    echo 'Active';
                                } else {
                                    echo 'Inactive';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="messageGreen">There are no workflows defined</div>
            <?php endif ?>
            <div class="ubirimiModalDialog" id="modalDeleteWorkflow"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>