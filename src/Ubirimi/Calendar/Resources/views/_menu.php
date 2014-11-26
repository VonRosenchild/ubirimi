<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

Util::renderMaintenanceMessage();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px">
        </td>
        <td style="height: 40px" valign="middle">
            <?php require_once __DIR__ . '/../../../Resources/views/productTopBar.php' ?>
        </td>
        <td style="padding-right: 12px;">
            <table align="right" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <td style="height:44px;" id="menu_top_user" width="58px" align="center" class="product-menu">
                        <span>
                            <img src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($session->get('user'), 'small') ?>" title="<?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>" height="33px" style="vertical-align: middle" />
                        </span>
                        <span class="arrow" style="top: 12px;"></span>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#6A8EB2" style="padding-left: 12px; padding-right: 12px;">
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>

                    <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'calendars') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuCalendars">
                        <span>Calendars</span>
                        <span class="<?php if ($menuSelectedCategory == 'calendars') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
        <td align="right" style="vertical-align: middle">
            <input type="button" id="btnCreateCalendar" value="Create Calendar" />
            <input id="calendar_quick_search" type="text" style="height: 15px; font-style: italic;" value="Quick Search" name="search" />
        </td>
    </tr>
</table>
<div id="contentMenuCalendars" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/calendar/calendars">My Calendars</a></div>
            </td>
        </tr>
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/calendar/shared-with-me-calendars">Shared with me</a></div>
            </td>
        </tr>

    </table>
</div>
<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />
<div style="display: none;" id="contentUserHome">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><?php echo LinkHelper::getUserProfileLink($loggedInUserId, SystemProduct::SYS_PRODUCT_YONGO, 'Profile', '', 'linkSubMenu') ?></div></td>
        </tr>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td><div><a class="linkSubMenu" href="/sign-out">Sign out</a></div></td>
        </tr>
    </table>
</div>
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<div class="ubirimiModalDialog" id="modalCreateCalendar"></div>
<div id="topMessageBox" align="center" class="topMessageBox"></div>