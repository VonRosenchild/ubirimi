<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'] . ' > Create Step';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <?php if ($addStepPossible): ?>
            <form name="add_step" action="/yongo/administration/workflow/add-step/<?php echo $workflowId ?>" method="post">
                <table width="100%">
                    <tr>
                        <td valign="top" width="150">Name <span class="error">*</span></td>
                        <td>
                            <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                            <?php if ($emptyName): ?>
                                <div class="error">The name can not be empty.</div>
                            <?php elseif ($duplicateName): ?>
                                <div class="error">A step with the same name already exists.</div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">Linked Status</td>
                        <td>
                            <select name="linked_status" class="select2Input">
                                <?php while ($status = $statuses->fetch_array(MYSQLI_ASSOC)): ?>
                                    <?php if (!in_array($status['id'], $linkedStatuses)): ?>
                                        <option value="<?php echo $status['id'] ?>"><?php echo $status['name'] ?></option>
                                    <?php endif ?>
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
                            <button type="submit" name="add_step" class="btn ubirimi-btn">Add Step</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-as-text/<?php echo $workflowId ?>">Cancel</a>
                        </td>
                    </tr>
                </table>
            </form>
            <?php else: ?>
            <div class="infoBox">
                <span>All existing issue statuses are used in this workflow.</span>
                <br />
                <span>Please <a href="/yongo/administration/issue/status/add">create</a> a new status if you would like to add another step to this workflow.</span>
            </div>
            <hr size="1" />
            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-as-text/<?php echo $workflowId ?>">Go Back</a>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>