<?php
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\WorkflowFunction;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' .
            '<a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . ' </a> > Transition: ' . $workflowData['transition_name'] . ' > Create Post Function';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <form name="add_post_function" action="/yongo/administration/workflow/transition-add-post-function/<?php echo $workflowDataId ?>" method="post">
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="24"></th>
                        <th width="150">Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <?php while ($postFunctions && $function = $postFunctions->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php
                        switch ($function['id']) {

                            case WorkflowFunction::FUNCTION_SET_ISSUE_FIELD_VALUE:
                                echo '<tr>';
                                    echo '<td>';
                                        echo '<input class="radio" type="radio" name="function" id="label_' . $function['id'] . '" value="' . $function['id'] . '">';
                                    echo '</td>';
                                    echo '<td>';
                                        echo '<label for="label_' . $function['id'] . '">' . $function['name'] . '</label>';
                                    echo '</td>';
                                    echo '<td>' . $function['description'] . '</td>';
                                echo '</tr>';
                                break;
                        }
                    ?>
                <?php endwhile ?>
                <?php if ($errors['no_function_selected']): ?>
                    <tr>
                        <td colspan="2">
                            <div class="error">No function selected.</div>
                        </td>
                    </tr>
                <?php endif ?>
            </table>
            <table width="100%">
                <tr>
                    <td align="left" colspan="2">
                        <hr size="1"/>
                        <div align="left">
                            <button type="submit" name="add_new_post_function" class="btn ubirimi-btn">Add Post Function</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/transition-post-functions/<?php echo $workflowDataId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>