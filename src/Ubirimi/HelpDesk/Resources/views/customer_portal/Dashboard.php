<?php
use Ubirimi\Util;

require __DIR__ . '/_header.php'; ?>
<body>
    <?php require __DIR__ . '/_menu.php'; ?>
    <?php
        Util::renderBreadCrumb('Dashboard');
    ?>

    <div class="pageContent">
        <?php if (isset($projectIds)): ?>
            <a href="/helpdesk/customer-portal/tickets?page=1&sort=created&order=desc&project=<?php echo implode('|', $projectIds) ?>&reporter=<?php echo $loggedInUserId ?>">My Tickets</a>
            <a href="/helpdesk/customer-portal/tickets?page=1&sort=created&order=desc&project=<?php echo implode('|', $projectIds) ?>">All Tickets</a>
        <?php else: ?>
            <div>There are no projects created yet.</div>
        <?php endif ?>
    </div>
</body>