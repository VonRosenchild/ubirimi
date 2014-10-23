<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Edit';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <form name="edit_transition" method="post" action="/yongo/administration/workflow/edit-transition/<?php echo $workflowData['id'] ?>">
            <table>
                <tr>
                    <td><div>Transition Name</div></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $workflowData['transition_name'] ?>" name="transition_name" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><div>Transition Description:</div></td>
                    <td>
                        <textarea name="transition_description" class="inputTextAreaLarge"><?php echo $workflowData['transition_description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td><div>Destination Step:</div></td>
                    <td>
                        <select name="step" class="select2InputSmall">
                            <?php while ($step = $steps->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($step['id'] == $workflowData['destination_step_id']) echo 'selected="selected"' ?> value="<?php echo $step['id'] ?>"><?php echo $step['step_name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><div>Transition Screen:</div></td>
                    <td>
                        <select name="screen" class="select2InputSmall">
                            <option value="-1">No Screen</option>
                            <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($screen['id'] == $workflowData['screen_id']) echo 'selected="selected"' ?> value="<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></option>
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
                    <td algin="left">
                        <button type="submit" name="edit_transition" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Transition</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-transition/<?php echo $workflowData['id'] ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>