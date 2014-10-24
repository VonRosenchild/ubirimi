<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Post Functions';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/workflow/view-transition/<?php echo $workflowDataId ?>">Summary</a></li>
            <li><a href="/yongo/administration/workflow/transition-browser/<?php echo $workflowDataId ?>">Workflow Browser</a></li>
            <li><a href="/yongo/administration/workflow/transition-conditions/<?php echo $workflowDataId ?>">Conditions</a></li>
            <li class="active"><a href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Post Functions</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnAddPostFunction" href="/yongo/administration/workflow/transition-add-post-function/<?php echo $workflowDataId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Post Function</a></td>
                <td><a id="btnEditPostFunction" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeletePostFunction" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($postFunctions): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Functions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($postFunction = $postFunctions->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $postFunction['id'] ?>">
                            <td width="22">
                                <input <?php if (!$postFunction['user_editable_flag'])
                                    echo 'disabled="disabled"' ?> type="checkbox" value="1" id="el_check_<?php echo $postFunction['id'] ?>"/>
                                <input type="hidden" value="<?php echo $postFunction['user_deletable_flag'] ?>" id="post_function_deletable_<?php echo $postFunction['id'] ?>"/>
                            </td>
                            <td>
                                <div><?php echo UbirimiContainer::get()['repository']->get(WorkflowFunction::class)->getFunctionDescription($postFunction) ?></div>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no post functions defined.</div>
        <?php endif ?>
    </div>

    <div class="ubirimiModalDialog" id="modalDeletePostFunction"></div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>