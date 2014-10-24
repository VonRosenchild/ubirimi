<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Conditions';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/workflow/view-transition/<?php echo $workflowDataId ?>">Summary</a></li>
            <li><a href="/yongo/administration/workflow/transition-browser/<?php echo $workflowDataId ?>">Workflow Browser</a></li>
            <li class="active"><a href="/yongo/administration/workflow/transition-conditions/<?php echo $workflowDataId ?>">Conditions</a></li>
            <li><a href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Post Functions</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/workflow/add-condition/<?php echo $workflowDataId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Condition</a></td>
                <td><a id="add_workflow_condition_open_bracket" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Open Bracket (</a></td>
                <td><a id="add_workflow_condition_closed_bracket" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Closed Bracket )</a></td>
                <td><a id="add_workflow_condition_operator_and" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Add AND Operator</a></td>
                <td><a id="add_workflow_condition_operator_or" href="#" class="btn ubirimi-btn"><i class="icon-plus"></i> Add OR Operator</a></td>
                <td><a id="btnDeleteAllConditions" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete All Conditions</a></td>
            </tr>
        </table>
        <?php if ($conditionString): ?>
            <div>The current conditions associated with this transition</div>
            <div class="headerPageText"><?php echo $conditionString ?></div>
            <br />
            <div>
                <span>Condition passes logical examination: </span>
                <?php if (UbirimiContainer::get()['repository']->get(Workflow::class)->checkLogicalConditionsByTransitionId($workflowDataId)): ?>
                    <span style="background-color: greenyellow; padding: 4px">YES</span>
                <?php else: ?>
                    <span style="background-color: red; padding: 4px">NO</span>
                <?php endif ?>
            </div>
        <?php else: ?>
            <div class="messageGreen">There are no conditions associated with this transition.</div>
        <?php endif ?>
        <input type="hidden" value="<?php echo $workflowDataId ?>" id="transition_id" />
        <div class="ubirimiModalDialog" id="modalDeleteAllConditions"></div>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>