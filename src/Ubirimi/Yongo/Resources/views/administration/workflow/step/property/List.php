<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../_menu.php'; ?>
    <?php
        $breadcrumb = '<a class="linkNoUnderline" href="/yongo/administration/workflows">Workflows</a> > <a class="linkNoUnderline" href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a> > Step: ' . $step['name'] . ' > Properties';
        Util::renderBreadCrumb($breadcrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/workflow/view-step/<?php echo $stepId ?>">Summary</a></li>
            <li class="active"><a href="/yongo/administration/workflow/view-step-properties/<?php echo $stepId ?>">Properties</a></li>
            <li><a href="/yongo/administration/workflow/step-browser/<?php echo $stepId ?>">Workflow Browser</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/yongo/administration/workflow/add-step-property/<?php echo $step['id'] ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Property</a>
                    <a href="#" id="btnEditStepProperty" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a>
                    <a id="btnDeleteStepProperty" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a>
                </td>
            </tr>
        </table>
        <?php if ($stepProperties): ?>
            <table class="table table-hover table-condensed">
                <tr>
                    <th></th>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
                <?php while ($stepProperties && $stepProperty = $stepProperties->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $stepProperty['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $stepProperty['id'] ?>" />
                        </td>
                        <td><?php echo $stepProperty['name'] ?></td>
                        <td><?php echo $stepProperty['value'] ?></td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no properties defined for this step.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteStepProperty"></div>
    <?php require_once __DIR__ . '/../../../_footer.php' ?>
</body>