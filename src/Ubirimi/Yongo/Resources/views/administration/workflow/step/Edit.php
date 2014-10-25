<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';

?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Step: ' . $step['name'] . ' > Edit';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <form action="/yongo/administration/workflow/edit-step/<?php echo $stepId ?>?source=<?php echo $source ?>" name="edit_step" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="110">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" class="inputText" name="name" value="<?php echo $step['name'] ?>" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>Linked Status</td>
                    <td>
                        <select name="status" class="select2InputSmall">
                            <?php while ($status = $statuses->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($status['id'] == $step['linked_issue_status_id']) echo 'selected="selected"' ?> value="<?php echo $status['id'] ?>"><?php echo $status['name'] ?></option>
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
                            <button type="submit" name="edit_step" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Step</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-step/<?php echo $stepId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>