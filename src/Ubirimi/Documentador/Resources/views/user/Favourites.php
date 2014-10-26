<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $title = $user['first_name'] . ' ' . $user['last_name'] . ' > Favourites';
        Util::renderBreadCrumb($title);
    ?>
    <div class="pageContent">
        <?php if (Util::checkUserIsLoggedIn()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnUserChangePassword" href="#" class="btn ubirimi-btn">Change Password</a></td>
                </tr>
            </table>
        <?php endif ?>

        <div class="messageGreen" id="userDataUpdated"></div>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'Summary', '') ?></li>
            <li class="active"><a href="/documentador/user/favourites/<?php echo $userId ?>">Favourites</a></li>
            <li><a href="/documentador/user/activity/<?php echo $userId ?>">Activity</a></li>
        </ul>

        <div style="height: 4px"></div>
        <?php if ($pages): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <?php while ($page = $pages->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><a href="/documentador/page/view/<?php echo $page['id'] ?>"><?php echo $page['name'] ?></a> by <?php echo LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) ?> (<?php echo Util::getFormattedDate($page['date_created'], $clientSettings['timezone']) ?>)</td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no pages.</div>
        <?php endif ?>
        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
    </div>
    <div class="ubirimiModalDialog" id="modalChangePassword"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>