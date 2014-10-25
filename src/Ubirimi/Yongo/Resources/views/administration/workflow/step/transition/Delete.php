<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'] . ' > Step: ' . $step['name'] . ' > Delete Transitions';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <div>Select transitions you want to delete for the <b><?php echo $step['name'] ?></b> step.</div>
        <form action="/yongo/administration/workflow/delete-transitions/<?php echo $stepId ?>" method="post" name="delete_transitions">
            <table width="100%">
                <tr>
                    <td width="100" valign="top">Transitions</td>
                    <td>
                        <select name="transitions[]" size="10" class="inputTextCombo" multiple="multiple">
                            <?php while ($transitions && $transition = $transitions->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $transition['id'] ?>"><?php echo $transition['transition_name'] ?></option>
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
                        <button type="submit" name="delete_transitions" class="btn ubirimi-btn">Delete Transitions</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-as-text/<?php echo $workflowId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>