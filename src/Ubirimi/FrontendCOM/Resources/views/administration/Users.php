<?php
require_once __DIR__ . '/_header.php';
?>
<body>
<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    Admin Home > Users > Overview
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($users): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnDeleteUser" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>First & Last Name</th>
                <th>Username</th>
                <th>Administrator</th>
                <th>Email</th>
                <th>Client</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($user = $users->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $user['id'] ?>">
                    <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $user['id'] ?>" /></td>
                    <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php if ($user['client_administrator_flag']) echo 'YES'; else echo 'NO'; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['client_company_name']; ?></td>
                    <td><?php echo date('d F', strtotime($user['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div id="modalDeleteClient"></div>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no projects yet.</div>
    <?php endif ?>
</div>
</body>
