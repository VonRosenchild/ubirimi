<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Workflow\WorkflowCondition;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Conditions';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="add_cond_parameters" action="/yongo/administration/workflow/add-condition-data/<?php echo $workflowDataId ?>?condition_id=<?php echo $conditionId ?>" method="post">
            <?php if ($conditionId == WorkflowCondition::CONDITION_PERMISSION): ?>
                <?php $permissionCategories = UbirimiContainer::get()['repository']->get(Permission::class)->getCategories() ?>
                <div>Add required paramters to condition</div>
                <table width="100%">
                    <tr>
                        <td width="200">Permission</td>
                        <td>
                            <select name="permission" class="select2InputMedium">
                                <?php while ($category = $permissionCategories->fetch_array(MYSQLI_ASSOC)): ?>
                                    <optgroup label="<?php echo $category['name'] ?>">
                                        <?php $permissions = UbirimiContainer::get()['repository']->get(Permission::class)->getByCategory($category['id']) ?>
                                        <?php while ($permission = $permissions->fetch_array(MYSQLI_ASSOC)): ?>
                                            <option value="<?php echo $permission['id'] ?>"><?php echo $permission['name'] ?></option>
                                        <?php endwhile ?>
                                    </optgroup>
                                <?php endwhile ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr size="1" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div align="left">
                                <button type="submit" name="confirm_new_condition_parameter" class="btn ubirimi-btn">Add</button>
                                <a class="btn ubirimi-btn" href="/yongo/administration/workflow/add-condition/<?php echo $workflowDataId ?>">Cancel</a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>