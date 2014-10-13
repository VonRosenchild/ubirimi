<?php
    use Ubirimi\Repository\User\User;

    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    Admin Home > Last Month Active Clients
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($clients): ?>
        <b>Total active clients last month: <?php echo $clients->num_rows ?></b>
        <br />
        <br />
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>Company name</th>
                <th># Users</th>
                <th>Log entries</th>
                <th>Domain and email</th>
                <th>Client Created Date</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($client = $clients->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td>
                        <?php echo $client['company_name'] ?><br />
                    </td>
                    <td>
                        <?php
                        $users = $this->getRepository('ubirimi.user.user')->getByClientId($client['id']);
                        if ($users)
                            echo $users->num_rows;
                        else echo 0;
                        ?>
                    </td>
                    <td><?php echo $client['log_entries'] ?></td>
                    <td>
                        <?php echo $client['company_domain'] ?><br />
                        <?php echo $client['contact_email'] ?>
                    </td>
                    <td><?php echo date('d F, Y', strtotime($client['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteClient"></div>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no clients yet.</div>
    <?php endif ?>
</div>
</body>
