<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

$styleSelectedMenu = 'style="background-color: #EEEEEE;';

    if (!isset($menuSelectedCategory)) {
        $menuSelectedCategory = null;
    }
    Util::renderMaintenanceMessage();

    $filterLogTo = date('Y-m-d');
    $filterLogFrom = date('Y-m-d', strtotime('-1 week'));
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px"></td>
        <td style="height: 40px" valign="middle">
            <?php require_once __DIR__ . '/../../../Resources/views/productTopBar.php' ?>

        </td>
        <td style="padding-right: 12px;">
            <?php if (Util::checkUserIsLoggedIn()): ?>
                <table align="right" border="0" cellpadding="0" cellspacing="0" style="height: 44px">
                    <tr>
                        <td id="menu_top_user" width="58px" align="center" class="product-menu">
                            <span>
                                <img src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($session->get('user'), 'small') ?>" title="<?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>" height="33px" style="vertical-align: middle" />
                            </span>
                            <span class="arrow" style="top: 12px;"></span>
                            &nbsp;
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <table align="right" border="0" cellpadding="0" cellspacing="0" style="height: 44px">
                    <tr>
                        <td style="height:44px; border-left: 1px #9c9c9c solid;" width="100px" class="product-menu" align="center" valign="middle">
                            <div>
                                <a href="<?php echo Util::getHttpHost() ?>" title="Log In">
                                    Log In
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
        </td>
    </tr>
</table>

<?php if ($session->get('selected_product_id') ==-1): ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6A8EB2" style="padding-left: 12px; padding-right: 12px;">
        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'general_home') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuHomeOverview">
                            <span><a style="<?php if ($menuSelectedCategory == 'general_home') echo 'color: black'; else echo 'color: white;' ?>" href="/general-settings/home" class="linkNoUnderline">Home</a></span>
                            &nbsp;
                        </td>
                        <?php if (Util::userHasClientAdministrationPermission()): ?>
                            <td class="menuItemBasic <?php if ($menuSelectedCategory == 'general_overview') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuHomeGeneral">
                                <span>Overview</span>
                                <span class="<?php if ($menuSelectedCategory == 'general_overview') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                                &nbsp;
                            </td>
                            <td width="8px"></td>
                            <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'general_mail') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuMailGeneral">
                                <span>Mail</span>
                                <span class="<?php if ($menuSelectedCategory == 'general_mail') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                                &nbsp;
                            </td>
                        <?php endif ?>
                        <td width="8px"></td>
                        <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'general_user') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuUsersGeneral">
                            <span>Users</span>
                            <span class="<?php if ($menuSelectedCategory == 'general_user') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                            &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div id="contentMenuHomeGeneral" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/view-general">General Settings</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/logs/<?php echo $filterLogFrom ?>/<?php echo $filterLogTo ?>">Logs</a></div>
                </td>
            </tr>
        </table>
    </div>

    <div id="contentMenuMailGeneral" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/smtp-settings">SMTP Settings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/main-queue">Mail Queue&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                </td>
            </tr>
            <?php if ($session->get('user/super_user_flag')): ?>
                <tr>
                    <td>
                        <div><a class="linkSubMenu" href="/general-settings/sent-email">Send Email</a></div>
                    </td>
                </tr>
            <?php endif ?>
        </table>
    </div>

    <div id="contentMenuUsersGeneral" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/users">Users</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/general-settings/users/profile-manager">Profile Manager</a></div>
                </td>
            </tr>
        </table>
    </div>

    <div style="border-left: 1px solid #c0c0c0;" id="contentMenuUsersGeneral"></div>
<?php elseif ($session->get('selected_product_id') ==-2): ?>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6A8EB2" style="padding-left: 12px; padding-right: 12px;">
        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'ubirimi_about') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuUbirimiAbout">
                            <span><a style="<?php if ($menuSelectedCategory == 'ubirimi_about') echo 'color: black'; else echo 'color: white;' ?>" href="/ubirimi/about" class="linkNoUnderline">About</a></span>
                            &nbsp;
                        </td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>
<?php endif ?>

<div id="contentUserHome" style="display: none;">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><?php echo LinkHelper::getUserProfileLink($loggedInUserId, SystemProduct::SYS_PRODUCT_YONGO, 'Profile', '', 'linkSubMenu') ?></div></td>
        </tr>
        <tr>
            <td><div><a class="linkSubMenu" href="/sign-out">Sign out</a></div></td>
        </tr>
    </table>
</div>
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />