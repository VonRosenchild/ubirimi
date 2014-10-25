<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflow['name'] . ' > Copy';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="copy_workflow" action="/yongo/administration/workflow/copy/<?php echo $workflowId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="200" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $workflow['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">A workflow with the same name already exists.</div>
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
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="let">
                        <div align="left">
                            <button type="submit" name="copy_workflow" class="btn ubirimi-btn">Copy Workflow</button>
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