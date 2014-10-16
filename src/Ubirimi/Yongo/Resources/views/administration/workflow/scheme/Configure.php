<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/workflows/schemes"?>Workflow Schemes</a> > <?php echo $workflowScheme['name'] ?> > Edit
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="add_status" action="/yongo/administration/workflows/edit-scheme/<?php echo $Id ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="200" valign="top">Name</td>
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
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td colspan="2">This scheme has the following workflows assigned</td>
                </tr>
                <?php while ($workflow = $allWorkflows->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td>
                            <?php
                                $found = false;
                                while ($schemeWorkflows && $schemeWorkflow = $schemeWorkflows->fetch_array(MYSQLI_ASSOC)) {
                                    if ($schemeWorkflow['workflow_id'] == $workflow['id']) {
                                        $found = true;
                                        break;
                                    }
                                }
                            ?>
                            <input <?php if ($found) echo 'checked="checked"' ?> type="checkbox" value="1" name="workflow_<?php echo $workflow['id'] ?>" />
                            <span><?php echo $workflow['name'] ?></span>
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
            <table width="100%">
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td width="200"></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_workflow_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/workflows/schemes">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>