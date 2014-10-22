<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php if (Util::userHasClientAdministrationPermission()): ?>
        <?php Util::renderBreadCrumb('Mail > Mail Queue') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <?php Util::renderBreadCrumb('Mail > Mail Queue') ?>
            <div class="infoBox">
                <span>This page shows you the current Ubirmi internal event queue, whose events may trigger notification emails.</span>
                <br />
                <span>The queue currently has <?php echo $total ?> items in it.</span>
            </div>

            <?php if ($mailsInQueue): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Subject</th>
                            <th>Queued</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($mail = $mailsInQueue->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr>
                                <td width="22"></td>
                                <td><?php echo $mail['subject']; ?></td>
                                <td><?php echo $mail['date_created']; ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/_footer.php' ?>
</body>