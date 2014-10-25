<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Summary';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/workflow/view-transition/<?php echo $workflowDataId ?>">Summary</a></li>
            <li><a href="/yongo/administration/workflow/transition-browser/<?php echo $workflowDataId ?>">Workflow Browser</a></li>
            <li><a href="/yongo/administration/workflow/transition-conditions/<?php echo $workflowDataId ?>">Conditions</a></li>
            <li><a href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Post Functions</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/yongo/administration/workflow/edit-transition/<?php echo $workflowData['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                    <a id="btnDeleteWorkflowTransition" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td><div class="textLabel">Transition Name:</div></td>
                <td><?php echo $workflowData['transition_name'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Transition Description:</div></td>
                <td><?php echo $workflowData['transition_description'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Destination Step:</div></td>
                <td><?php echo $workflowData['destination_step_name'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Transition Screen:</div></td>
                <td>
                    <?php if ($workflowData['screen_name']): ?>
                        <?php echo $workflowData['screen_name'] ?>
                    <?php else: ?>
                        <span>None</span>
                    <?php endif ?>
                </td>
            </tr>
        </table>
        <input type="hidden" value="<?php echo $workflowData['id'] ?>" id="transition_id" />
        <input type="hidden" value="<?php echo $workflow['id'] ?>" id="workflow_id" />
        <div id="deleteWorkflowTransitionModal" class="ubirimiModalDialog"></div>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>