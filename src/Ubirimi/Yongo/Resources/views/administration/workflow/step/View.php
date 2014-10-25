<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Step: ' . $step['name'] . ' > Summary';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/workflow/view-step/<?php echo $stepId ?>">Summary</a></li>
            <li><a href="/yongo/administration/workflow/view-step-properties/<?php echo $stepId ?>">Properties</a></li>
            <li><a href="/yongo/administration/workflow/step-browser/<?php echo $stepId ?>">Workflow Browser</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/yongo/administration/workflow/edit-step/<?php echo $step['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                    <a href="/yongo/administration/workflow/add-transition/<?php echo $step['id'] ?>" class="btn ubirimi-btn">Add Outgoing Transition</a>
                    <a id="btnDeleteStepOutgoingTransitions" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete Outgoing Transitions</a>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td><div class="textLabel">Step Name:</div></td>
                <td><?php echo $step['name'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Linked Status:</div></td>
                <td><?php echo $step['status_name'] ?></td>
            </tr>
        </table>
        <input type="hidden" value="<?php echo $stepId ?>" id="step_id" />
        <input type="hidden" value="<?php echo $workflowId ?>" id="workflow_id" />
        <div id="deleteStepOutgoingTransitions"></div>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>