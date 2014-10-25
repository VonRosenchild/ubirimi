<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'] . ' > Create Transition';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="add_transition" action="/yongo/administration/workflow/add-transition/<?php echo $workflowStepId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Destination Step</td>
                    <td>
                        <select name="step" class="select2InputSmall">
                            <?php while ($step = $steps->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $step['id'] ?>"><?php echo $step['step_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Screen</td>
                    <td>
                        <select name="screen" class="select2InputSmall">
                            <option value="-1">No Screen</option>
                            <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></option>
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
                            <button type="submit" name="add_transition" class="btn ubirimi-btn">Add Transition</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-as-text/<?php echo $workflowId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>