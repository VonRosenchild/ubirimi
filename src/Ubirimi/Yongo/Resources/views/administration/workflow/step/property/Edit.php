<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > ' . $workflowMetadata['name'] . ' > Step: ' . $step['name'] . ' > Update Property';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <form name="add_step" action="/yongo/administration/workflow/edit-step-property/<?php echo $stepPropertyId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="150">Key <span class="error">*</span></td>
                    <td>
                        <select name="key" class="select2Input">
                            <?php while ($propertyData = $allProperties->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($propertyData['id'] == $stepProperty['sys_workflow_step_property_id']) echo 'selected="selected"' ?> value="<?php echo $propertyData['id'] ?>"><?php echo $propertyData['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                        <?php if ($duplicateKey): ?>
                            <div class="error">This step already has a property of this kind.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Value <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($value)) echo $value; ?>" name="value" />
                        <?php if ($emptyValue): ?>
                            <div class="error">The value can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <button type="submit" name="edit_property" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Property</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/workflow/view-step-properties/<?php echo $stepId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>