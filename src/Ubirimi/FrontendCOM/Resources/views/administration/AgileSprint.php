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
                    Admin Home > Agile Sprints > Overview
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td><a href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
        </tr>
    </table>

    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Client</th>
            <th>Date Start</th>
            <th>Date End</th>
            <th>Started</th>
            <th>Finished</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($agileSprint = $agileSprints->fetch_array(MYSQLI_ASSOC)): ?>
            <tr id="table_row_<?php echo $agileSprint['id'] ?>">
                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $agileSprint['id'] ?>" /></td>
                <td><?php echo $agileSprint['name']; ?></td>
                <td><?php echo $agileSprint['company_name'] . ' / ' . $agileSprint['company_domain']; ?></td>
                <td><?php echo $agileSprint['date_start']; ?></td>
                <td><?php echo $agileSprint['date_end']; ?></td>
                <td><?php echo $agileSprint['started_flag']; ?></td>
                <td><?php echo $agileSprint['finished_flag']; ?></td>
                <td><?php echo $agileSprint['date_created']; ?></td>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
</div>
</body>
