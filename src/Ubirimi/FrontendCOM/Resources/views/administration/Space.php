<?php

use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;

    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    Admin Home > Agile Boards > Spaces
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
            <th>Description</th>
            <th>Client</th>
            <th>Created By</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($space = $spaces->fetch_array(MYSQLI_ASSOC)): ?>
            <tr id="table_row_<?php echo $space['id'] ?>">
                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $space['id'] ?>" /></td>
                <td><?php echo $space['name']; ?></td>
                <td><?php echo $space['description']; ?></td>
                <td>
                    <?php
                    $client = $this->getRepository(UbirimiClient::class)->getById($space['client_id']);
                    echo $client['company_name'];
                    ?>
                </td>
                <td>
                    <?php
                    $user = $this->getRepository(UbirimiUser::class)->getById($space['user_created_id']);
                    echo $user['first_name'] . ' ' . $user['last_name'];
                    ?>
                </td>
                <td><?php echo date('d F', strtotime($space['date_created'])) ?></td>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
</div>
</body>
