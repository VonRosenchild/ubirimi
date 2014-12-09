<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Filters > ' . $filter['name'] . ' > Subscriptions') ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNewFilterSubscription" class="btn ubirimi-btn"><i class="icon-plus"></i> Add Subscription</a></td>
                <td><a id="btnEditFilterSubscription" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteFilterSubscription" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>Subscriber</th>
                <th>Subscribed</th>
                <th>Schedule</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($subscription = $subscriptions->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $subscription['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $subscription['id'] ?>" />
                    </td>
                    <td><?php echo $subscription['created_first_name'] . ' ' . $subscription['created_last_name'] ?></td>
                    <td>
                        <?php if ($subscription['group_name']): ?>
                            <div><?php echo $subscription['group_name'] ?></div>
                        <?php else: ?>
                            <div><?php echo $subscription['first_name'] . ' ' . $subscription['last_name'] ?></div>
                        <?php endif ?>
                    </td>
                    <td><?php echo $subscription['period'] ?></td>
                    <td><a href="#">Edit</a> | <a href="/yongo/filter/subscription/delete/<?php echo $subscription['id'] ?>">Delete</a></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div id="addFilterSubscriptionModal"></div>

        <input type="hidden" value="<?php echo $filterId ?>" id="filter_id" />
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>