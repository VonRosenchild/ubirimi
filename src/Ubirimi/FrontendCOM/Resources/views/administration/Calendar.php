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
                    Admin Home > Calendars
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>
    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td><a id="btnDeleteClient" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
        </tr>
    </table>
    <?php if ($calendars): ?>
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Client</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><?php echo $calendar['name']; ?></td>
                    <td><?php echo $calendar['description']; ?></td>
                    <td><?php echo $calendar['company_domain']; ?></td>
                    <td><?php echo date('d F', strtotime($calendar['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    <?php else: ?>
        <div>There are no calendars created.</div>
    <?php endif ?>
</div>
</body>
