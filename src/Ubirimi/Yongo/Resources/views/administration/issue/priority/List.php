<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Issue Priorities') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/yongo/administration/issue/statuses">Statuses</a></li>
                <li><a href="/yongo/administration/issue/resolutions">Resolutions</a></li>
                <li class="active"><a href="/yongo/administration/issue/priorities">Priorities</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/priority/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Priority</a></td>
                    <?php if ($priorities): ?>
                        <td><a id="btnEditIssuePriority" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeleteIssuePriority" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>

            <?php if ($priorities): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="200">Name</th>
                            <th width="550">Description</th>
                            <th>Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($priority = $priorities->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $priority['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $priority['id'] ?>" />
                                </td>
                                <td>
                                    <img height="16px" src="/yongo/img/issue_priority/<?php echo $priority['icon_name'] ?>" />
                                    <?php echo $priority['name']; ?>
                                </td>
                                <td><?php echo $priority['description']; ?></td>
                                <td>
                                    <div style="background-color:<?php echo $priority['color'] ?>; width: 20px; height: 20px;">&nbsp;</div>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="messageGreen">There are no issue priorities defined.</div>
            <?php endif ?>
            <div id="modalDeleteIssuePriority"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>