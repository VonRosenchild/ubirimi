<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\IssueEvent;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflowPostFunctionData['workflow_id'] . '">' . $workflowPostFunctionData['workflow_name'] . '</a> > Transition: ' . $workflowPostFunctionData['transition_name'] . ' > Edit Parameters To Function';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="edit_post_function_data" action="/yongo/administration/workflow/edit-post-function-data/<?php echo $workflowPostFunctionDataId; ?>" method="post">
            <?php if ($postFunctionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE): ?>
                <?php $resolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId); ?>
                <div>Edit required parameters to the function <b><?php echo $workflowPostFunctionData['name'] ?></b></div>
                <table width="100%">
                    <tr>
                        <td width="150">Issue Field</td>
                        <td>
                            <select name="issue_field" class="select2InputSmall">
                                <option value="<?php echo Field::FIELD_RESOLUTION_CODE ?>">Resolution</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Field Value</td>
                        <td>
                            <select name="field_value" class="select2InputSmall">
                                <option value="-1" <?php if ($fieldValue == -1) echo 'selected="selected"' ?>>Clear Value</option>
                                <?php while ($resolution = $resolutions->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option  <?php if ($fieldValue == $resolution['id']) echo 'selected="selected"' ?> value="<?php echo $resolution['id'] ?>"><?php echo $resolution['name'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="left">
                            <div align="left">
                                <button type="submit" name="edit_parameters" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Parameters</button>
                                <a class="btn ubirimi-btn" href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowPostFunctionData['workflow_data_id'] ?>">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php elseif ($postFunctionId == WorkflowFunction::FUNCTION_FIRE_EVENT): ?>
                <div>Update required parameters to the function <b><?php echo $workflowPostFunctionData['name'] ?></b></div>
                <table width="100%">
                    <tr>
                        <td width="150">Event</td>
                        <td>
                            <?php $events = UbirimiContainer::get()['repository']->get(IssueEvent::class)->getByClient($clientId); ?>
                            <select name="fire_event" class="select2Input">

                                <?php while ($event = $events->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option value="<?php echo $event['id'] ?>"><?php echo $event['name'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr size="1" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="left">
                            <div align="left">
                                <button type="submit" name="edit_parameters" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Parameters</button>
                                <a class="btn ubirimi-btn" href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowPostFunctionData['workflow_data_id'] ?>">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
            <input type="hidden" name="function_id" value="<?php echo $postFunctionId ?>" />
            <input type="hidden" name="workflow_data_id" value="<?php echo $workflowPostFunctionData['workflow_data_id'] ?>" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>