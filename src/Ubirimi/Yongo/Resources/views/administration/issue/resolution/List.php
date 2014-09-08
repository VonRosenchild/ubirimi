<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
        <?php Util::renderBreadCrumb('Issue Resolutions') ?>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/issue/statuses">Statuses</a></li>
            <li class="active"><a href="/yongo/administration/issue/resolutions">Resolutions</a></li>
            <li><a href="/yongo/administration/issue/priorities">Priorities</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/issue/resolution/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Resolution</a></td>
                <?php if ($resolutions): ?>
                    <td><a id="btnEditIssueResolution" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteIssueResolution" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>
        <?php if ($resolutions): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th width="200">Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($resolution = $resolutions->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $resolution['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $resolution['id'] ?>"/></td>
                            <td><?php echo $resolution['name']; ?></td>
                            <td><?php echo $resolution['description']; ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            <div id="deleteIssueSetting"></div>
            <input type="hidden" value="resolution" id="setting_type"/>
        <?php else: ?>
            <div class="messageGreen">There are no issue resolutions defined.</div>
        <?php endif ?>
    </div>
        <div class="ubirimiModalDialog" id="modalDeleteIssueResolution"></div>
    <?php else: ?>
        <?php Util::renderContactSystemAdministrator() ?>
    <?php
        endif ?>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>