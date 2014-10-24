<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Workflow Browser';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/workflow/view-transition/<?php echo $workflowDataId ?>">Summary</a></li>
            <li class="active"><a href="/yongo/administration/workflow/transition-browser/<?php echo $workflowDataId ?>">Workflow Browser</a></li>
            <li><a href="/yongo/administration/workflow/transition-conditions/<?php echo $workflowDataId ?>">Conditions</a></li>
            <li><a href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Post Functions</a></li>
        </ul>

        <div class="separationVertical"></div>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="3" align="center">Workflow Browser</td>
            </tr>
            <tr>
                <td>Originating Steps</td>
                <td></td>
                <td>Destination Steps</td>
            </tr>
            <tr>
                <td>
                    <?php $steps = UbirimiContainer::get()['repository']->get(Workflow::class)->getOriginatingStepsForTransition($workflow['id'], $workflowData['transition_name']); ?>
                    <?php if ($steps): ?>
                        <table>
                            <?php while ($step = $steps->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $step['step_name'] ?></td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    <?php endif ?>
                </td>
                <td valign="top" align="center"><?php echo $workflowData['transition_name'] ?></td>
                <td valign="top">
                    <?php $steps = UbirimiContainer::get()['repository']->get(Workflow::class)->getDestinationStepsForTransition($workflow['id'], $workflowData['transition_name']); ?>
                    <?php if ($steps): ?>
                        <table>
                            <?php while ($step = $steps->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $step['step_name'] ?></td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    <?php endif ?>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>