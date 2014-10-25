<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <div class="pageContent">
        <?php
            $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Transition: ' . $workflowData['transition_name'] . ' > Conditions';
            Util::renderBreadCrumb($breadcrumb);
        ?>

        <form name="add_post_function" action="/yongo/administration/workflow/add-condition-data/<?php echo $workflowDataId ?>" method="post">
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="24"></th>
                        <th width="200">Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <?php while ($conditions && $condition = $conditions->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php
                        echo '<tr>';
                            echo '<td>';
                                echo '<input ' . $checkedHTML . ' class="radio" type="radio" name="condition" id="label_' . $condition['id'] . '" value="' . $condition['id'] . '">';
                            echo '</td>';
                            echo '<td>';
                                echo '<label for="label_' . $condition['id'] . '">' . $condition['name'] . '</label>';
                            echo '</td>';
                            echo '<td>' . $condition['description'] . '</td>';
                        echo '</tr>';
                        $checkedHTML = '';
                    ?>
                <?php endwhile ?>
            </table>
            <table width="100%">
                <tr>
                    <td align="left" colspan="2">
                        <hr size="1"/>
                        <div align="left">
                            <button type="submit" name="confirm_new_issue" class="btn ubirimi-btn">Add Condition</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflow/transition-conditions/<?php echo $workflowDataId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>