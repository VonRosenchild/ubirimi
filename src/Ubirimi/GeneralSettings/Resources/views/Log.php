<?php
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php if (Util::userHasClientAdministrationPermission()): ?>
        <?php Util::renderBreadCrumb('Logs') ?>
    <?php endif ?>
    <div class="pageContent">
        <?php if (Util::userHasClientAdministrationPermission()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td valign="middle">
                        <span>From: </span>
                        <input type="text"
                               id="log_filter_from_date"
                               class="inputText"
                               style="width: 80px" value="<?php if (isset($from)) echo $from ?>"
                               name="log_filter_from_date" />
                        <span>To: </span>
                        <input type="text"
                               id="log_filter_to_date"
                               style="width: 80px"
                               class="inputText"
                               value="<?php if (isset($to)) echo $to ?>"
                               name="log_filter_to_date" />
                        <a id="btnFilterLog" href="#" class="btn ubirimi-btn">Filter</a>
                    </td>
                </tr>
            </table>

            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>User</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($log = $logs->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td>
                            <?php
                                switch ($log['sys_product_id']) {
                                    case SystemProduct::SYS_PRODUCT_YONGO:
                                        echo 'Yongo';
                                        break;
                                    case SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS:
                                        echo 'General';
                                        break;
                                    case SystemProduct::SYS_PRODUCT_CALENDAR:
                                        echo 'Events';
                                        break;
                                    case SystemProduct::SYS_PRODUCT_CHEETAH:
                                        echo 'Cheetah';
                                        break;
                                    case SystemProduct::SYS_PRODUCT_DOCUMENTADOR:
                                        echo 'Documentador';
                                        break;
                                    case SystemProduct::SYS_PRODUCT_SVN_HOSTING:
                                        echo 'SVN Hosting';
                                        break;
                                }
                            ?>
                        </td>
                        <td>
                            <?php echo $log['first_name'] . ' ' . $log['last_name']; ?>
                        </td>
                        <td><?php echo $log['message'] ?></td>
                        <td><?php echo $log['date_created'] ?></td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteUser"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>