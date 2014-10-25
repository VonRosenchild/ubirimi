<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a href="/yongo/administration/workflows" class="linkNoUnderline">Workflows</a> > Create Workflow') ?>

    <div class="pageContent">
        <form name="add_status" action="/yongo/administration/workflow/add" method="post">

            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($workflowExists): ?>
                            <div class="error">A workflow with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Workflow Issue Type Scheme</td>
                    <td>
                        <select name="workflow_issue_type_scheme" class="inputTextCombo">
                            <?php while ($scheme = $workflowIssueTypeSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $scheme['id'] ?>"><?php echo $scheme['name']; ?></option>
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
                            <button type="submit" name="new_workflow" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Workflow</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflows">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>