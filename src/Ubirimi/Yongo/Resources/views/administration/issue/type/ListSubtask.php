<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Sub-Tasks Issue Types') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/issue-types">Issue Types</a></li>
                <li><a href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a></li>
                <li class="active"><a href="/yongo/administration/issue-sub-tasks">Sub-Tasks</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/add-type?type=sub_task" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Sub-Task</a></td>
                    <?php if ($types): ?>
                        <td><a id="btnEditIssueType" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeleteIssueType" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>
            <?php if ($types): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($type = $types->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $type['id'] ?>">
                                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $type['id'] ?>" /></td>
                                <td>
                                    <div><?php echo $type['name'] ?></div>
                                </td>
                                <td><?php echo $type['description'] ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <div class="ubirimiModalDialog" id="modalDeleteIssueType"></div>
                <input type="hidden" value="type" id="setting_type" />
            <?php else: ?>
                <div class="messageGreen">There are no sub-tasks defined.</div>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>