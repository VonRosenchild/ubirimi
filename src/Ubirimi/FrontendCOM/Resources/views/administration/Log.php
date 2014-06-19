<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <?php Util::renderBreadCrumb("Admin Home > Log > Overview") ?>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($logs): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnDeleteProject" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>Client</th>
                <th>User</th>
                <th>Message</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($log = $logs->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $log['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $log['id'] ?>"/>
                    </td>
                    <td><?php echo $log['company_domain']; ?></td>
                    <td><?php echo $log['first_name'] . ' ' . $log['last_name']; ?></td>
                    <td><?php echo $log['message']; ?></td>
                    <td><?php echo date('d F', strtotime($log['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no logs yet.</div>
    <?php endif ?>
</div>
</body>