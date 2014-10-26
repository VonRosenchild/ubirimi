<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $title = $user['first_name'] . ' ' . $user['last_name'] . ' > Profile';
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
            <li class="active"><?php echo LinkHelper::getUserProfileLink($userId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'Summary', '') ?></li>
            <li><a href="/documentador/user/favourites/<?php echo $userId ?>">Favourites</a></li>
            <li><a href="/documentador/user/activity/<?php echo $userId ?>">Activity</a></li>
        </ul>

        <table width="100%">
            <tr>
                <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">User details</span></td>
            </tr>
            <tr>
                <td width="200px;"><div class="textLabel">Full Name:</div></td>
                <td><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Username:</div></td>
                <td><?php echo $user['username'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Email address:</div></td>
                <td><?php echo $user['email'] ?></td>
            </tr>
            <tr>
                <td><div class="textLabel">Groups:</div></td>
                <td>
                    <?php if ($groups): ?>
                        <?php while ($group = $groups->fetch_array(MYSQLI_ASSOC)): ?>
                            <?php $groups_arr[] = $group['name'] ?>
                        <?php endwhile ?>
                        <span><?php echo implode($groups_arr, ', ') ?></span>
                    <?php endif ?>
                </td>
            </tr>
        </table>

        <input type="hidden" id="user_id" value="<?php echo $userId ?>"/>
    </div>
    <div class="ubirimiModalDialog" id="modalChangePassword"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>