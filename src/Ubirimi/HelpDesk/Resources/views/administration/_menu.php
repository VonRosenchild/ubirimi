<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

$loggedInUserFirstName = $session->get('user/first_name');
    $loggedInUserLastName = $session->get('user/last_name');

    Util::renderMaintenanceMessage();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px"></td>
        <td style="height: 40px" valign="middle">
            <?php require_once __DIR__ . '/../../../../../Ubirimi/Resources/views/productTopBar.php' ?>
        </td>
        <td style="padding-right: 12px;">
            <table align="right" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <td id="menu_top_user" width="58px" align="center" class="product-menu">
                        <span>
                            <img src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($session->get('user'), 'small') ?>" title="<?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>" height="33px" style="vertical-align: middle" />
                        </span>
                        <span class="arrow" style="top: 12px;"></span>
                        &nbsp;
                    </td>

                    <td style="height:44px; border-left: 1px #9c9c9c solid;" width="190px" class="product-menu" align="center" valign="middle">
                        <div>
                            <a href="/documentador/dashboard/spaces">Exit Administration</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#6A8EB2" style="padding-left: 12px; padding-right: 12px;">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'helpdesk_administration') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminHelpdeskHome">
                        <span><a style="<?php if ($menuSelectedCategory == 'helpdesk_administration') echo 'color: black'; else echo 'color: white;' ?>" href="/helpdesk/administration" class="linkNoUnderline">Administration</a></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'helpdesk_organizations') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminHelpdeskOrganizations">
                        <span>Organizations</span>
                        <span class="<?php if ($menuSelectedCategory == 'helpdesk_organizations') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
        <td align="right">
            <input type="text" size="28" style="height: 15px; font-style: italic;" value="Administration Quick Search" name="search" />
        </td>
    </tr>
</table>

<div id="contentMenuAdminHelpdeskOrganizations" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/helpdesk/administration/organizations">All Organizations</a></div>
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