<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'] . ' > As Text';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a id="btnNewStep" href="/yongo/administration/workflow/add-step/<?php echo $workflowId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Step</a>
                    <a id="btnEditWorkflowStep" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a>
                    <a id="btnDeleteWorkflowStep" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a>
                    <a id="btnNewTransitionForStep" href="#" class="btn ubirimi-btn disabled">Add Transition</a>
                    <a id="btnDeleteTransitions" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete Transitions</a>
                </td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Step Name</th>
                    <th>Linked Status</th>
                    <th align="left">Transitions</th>
                </tr>
            </thead>

            <?php while ($step = $workflowSteps->fetch_array(MYSQLI_ASSOC)): ?>
                <?php
                    $incomingTransitions = UbirimiContainer::get()['repository']->get(Workflow::class)->getIncomingTransitionsForStep($workflowId, $step['id']);
                ?>
                <tr id="table_row_<?php echo $step['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $step['id'] ?>"/>
                        <input type="hidden" value="<?php if ($incomingTransitions) echo "0"; else echo "1" ?>" id="delete_workflow_step_possible_<?php echo $step['id'] ?>"/>
                    </td>
                    <td><a href="/yongo/administration/workflow/view-step/<?php echo $step['id'] ?>"><?php echo $step['step_name'] ?></a></td>
                    <td><?php echo $step['status_name'] ?></td>
                    <td align="left">
                        <?php
                            $transitions = UbirimiContainer::get()['repository']->get(Workflow::class)->getTransitionsForStepId($workflowId, $step['id']); ?>
                        <?php while ($transitions && $transition = $transitions->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><a href="/yongo/administration/workflow/view-transition/<?php echo $transition['id'] ?>"><?php echo $transition['transition_name'] ?></a> >> <?php echo $transition['name'] ?></div>
                        <?php endwhile ?>
                    </td>
                </tr>
            <?php endwhile ?>
        </table>
    </div>

    <div class="ubirimiModalDialog" id="modalDeleteWorkflowStep"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>