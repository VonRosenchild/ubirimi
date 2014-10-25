<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div id="buttons_workflow">
        <?php
            $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'];
            Util::renderBreadCrumb($breadcrumb);
        ?>
    </div>
    <div class="pageContent" style="height: 900px;">


        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td align="right"><a id="btnSaveWorkflow" href="#" class="btn ubirimi-btn">Done editing</a></td>
                <td><a href="/yongo/administration/workflows" class="btn ubirimi-btn">Cancel</a></td>
            </tr>
        </table>

        <div style="padding-top: 20px; position: absolute; width: 90%; height: 90%;">
            <?php
                $top = 8;
                while ($step = $steps->fetch_array(MYSQLI_ASSOC)) {
                    if ($step['step_name'] == 'Create Issue') {
                        echo '<input type="hidden" name="first_step" id="first_step" value="' . $step['id'] . '" />';
                        $stepFromInitialStepData = UbirimiContainer::get()['repository']->get(Workflow::class)->getTransitionsForStepId($workflowId, $step['id']);
                        $stepFromInitialStep = $stepFromInitialStepData->fetch_array(MYSQLI_ASSOC);
                        echo '<input type="hidden" id="first_transition" value="' . $step['id'] . '_' . $stepFromInitialStep['workflow_step_id_to'] . '">';
                    }
                    echo '<div class="node" style="top: ' . $top . 'em" id="node_status_' . $step['id'] . '">';
                    echo $step['step_name'] . ' <img class="menu_img" style="margin-top:4px;" width="18px" src="/img/settings.png" />';
                    echo '<div class="ep"></div>';
                    echo '</div>' . "\n";
                    $top += 6;
                }
            ?>
        </div>
        <input type="hidden" id="workflow_id" value="<?php echo $workflowId ?>"/>
        <input type="hidden" id="initial_rendering" value="1"/>

        <div id="setTransitionScreen"></div>
        <div id="setTransitionPostFunction"></div>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>