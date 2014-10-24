<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\Util;

$styleSelectedMenu = 'style="background-color: #EEEEEE;';

if (!isset($menuSelectedCategory))
    $menuSelectedCategory = null;

Util::renderMaintenanceMessage();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px"></td>
        <td style="height: 40px" valign="middle">
            <?php require_once __DIR__ . '/../../../../Resources/views/productTopBar.php' ?>
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
                            <a href="/svn-hosting/repositories">Exit Administration</a>
                        </div>
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
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'svn') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuSVNAdministrationHome">
                        <span>Administration</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />
<input type="hidden" value="1" id="has_administration_perm" />
<div style="display: none;" id="contentUserHome">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><a class="linkSubMenu" href="/sign-out">Sign out</a></div></td>
        </tr>
    </table>
</div>
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<div class="ubirimiModalDialog" id="modalKeyboardShortcuts"></div>
<div id="topMessageBox" align="center" class="topMessageBox"></div>