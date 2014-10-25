<?php

use Ubirimi\Documentador\Repository\Space\Space;
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
                    Admin Home > Agile Boards > Entities
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
            <th>From Space</th>
            <th>Created By</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($entity = $entities->fetch_array(MYSQLI_ASSOC)): ?>
            <tr id="table_row_<?php echo $entity['id'] ?>">
                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $entity['id'] ?>" /></td>
                <td><?php echo $entity['name']; ?></td>
                <td>
                    <?php
                        $space = UbirimiContainer::get()['repository']->get(Space::class)->getById($entity['documentator_space_id']);
                        echo $space['name'];
                    ?>
                </td>
                <td>
                    <?php
                    $user = $this->getRepository(UbirimiUser::class)->getById($entity['user_created_id']);
                    echo $user['first_name'] . ' ' . $user['last_name'];
                    ?>
                </td>
                <td><?php echo date('d F', strtotime($entity['date_created'])) ?></td>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
</div>
</body>
