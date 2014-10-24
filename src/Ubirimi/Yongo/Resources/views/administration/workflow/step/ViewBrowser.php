<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

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
            <li><a href="/yongo/administration/workflow/view-step/<?php echo $stepId ?>">Summary</a></li>
            <li><a href="/yongo/administration/workflow/view-step-properties/<?php echo $stepId ?>">Properties</a></li>
            <li class="active"><a href="/yongo/administration/workflow/step-browser/<?php echo $stepId ?>">Workflow Browser</a></li>
        </ul>

        <div class="separationVertical"></div>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="3" align="center">Workflow Browser</td>
            </tr>
            <tr>
                <td>Incoming Transitions</td>
                <td></td>
                <td>Outgoing Transitions</td>
            </tr>
            <tr>
                <td>
                    <?php
                        $transitions = UbirimiContainer::get()['repository']->get(Workflow::class)->getIncomingTransitionsForStep($workflow['id'], $stepId); ?>
                    <?php if ($transitions): ?>
                        <table>
                            <?php while ($transition = $transitions->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $transition['transition_name'] ?></td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    <?php endif ?>
                </td>
                <td valign="top" align="center"><?php echo $step['name'] ?></td>
                <td valign="top">
                    <?php
                        $transitions = UbirimiContainer::get()['repository']->get(Workflow::class)->getOutgoingTransitionsForStep($workflow['id'], $stepId); ?>
                    <?php if ($transitions): ?>
                        <table>
                            <?php while ($transition = $transitions->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr>
                                <td><?php echo $transition['transition_name'] ?></td>
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