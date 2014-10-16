<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <?php echo $workflow['name'] ?> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_workflow" action="/yongo/administration/workflow/edit/<?php echo $workflowId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="200" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $workflow['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                        <div class="error">The name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $workflow['description'] ?></textarea>
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
                    <td align="let">
                        <div align="left">
                            <button type="submit" name="edit_workflow" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Workflow</button>
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