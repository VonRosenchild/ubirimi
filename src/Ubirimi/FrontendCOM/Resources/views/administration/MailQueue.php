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
                    Admin Home > Mail Queue
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>
    <?php if (!$mails): ?>
        <div>There are no mails in the queue.</div>
    <?php else: ?>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Client</th>
                <th>From</th>
                <th>To</th>
                <th>Subject</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($mail = $mails->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><?php echo $mail['company_domain']; ?></td>
                    <td><?php echo $mail['from_address']; ?></td>
                    <td><?php echo $mail['to_address']; ?></td>
                    <td><?php echo $mail['subject']; ?></td>
                    <td><?php echo date('d F', strtotime($mail['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    <?php endif ?>
</div>
</body>