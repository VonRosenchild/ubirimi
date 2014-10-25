<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > ' . $project['name'] . ' > Workflow Scheme > Select a Different Scheme</div>';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <div>Select a workflow scheme you want to associate</div>

        <form name="select_workflow_scheme" method="post" action="/yongo/administration/project/workflows/select-project-workflow-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td width="200">Workflow Scheme</td>
                    <td>
                        <select name="workflow_scheme" class="select2InputMedium">
                            <?php while ($workflowScheme = $workflowSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($project['workflow_scheme_id'] == $workflowScheme['id']) echo 'selected="selected"' ?> value="<?php echo $workflowScheme['id'] ?>"><?php echo $workflowScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="associate" class="btn ubirimi-btn">Associate</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>