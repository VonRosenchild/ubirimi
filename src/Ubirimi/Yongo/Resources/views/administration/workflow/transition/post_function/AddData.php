<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Field\Field;
use Ubirimi\Yongo\Repository\Issue\IssueSettings;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Create Parameters To Function';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <form name="add_post_function_data" action="/yongo/administration/workflow/transition-add-post-function-data/<?php echo $workflowDataId ?>?function_id=<?php echo $postFunctionId ?>" method="post">
            <?php if ($postFunctionId == WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE): ?>
                <?php $resolutions = UbirimiContainer::get()['repository']->get(IssueSettings::class)->getAllIssueSettings('resolution', $clientId); ?>
                <div>Add required parameters to the function <b><?php echo $postFunctionSelected['name'] ?></b></div>
                <table width="100%">
                    <tr>
                        <td width="150">Issue Field</td>
                        <td>
                            <select name="issue_field" class="select2InputSmall">
                                <option value="<?php
                                    echo Field::FIELD_RESOLUTION_CODE ?>">Resolution</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Field Value</td>
                        <td>
                            <select name="field_value" class="select2InputSmall">
                                <option value="-1">Clear Value</option>
                                <?php while ($resolution = $resolutions->fetch_array(MYSQLI_ASSOC)): ?>
                                    <option value="<?php echo $resolution['id'] ?>"><?php echo $resolution['name'] ?></option>
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
                                <button type="submit" name="add_parameters" class="btn ubirimi-btn">Add Parameters</button>
                                <a class="btn ubirimi-btn" href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>