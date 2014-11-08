<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

$loggedInUserFirstName = $session->get('user/first_name');
$loggedInUserLastName = $session->get('user/last_name');

if (!isset($menuSelectedCategory))
    $menuSelectedCategory = null;

$hasDocumentadorGlobalAdministrationPermission = $session->get('user/documentator/is_global_administrator');
$hasDocumentadorGlobalSystemAdministrationPermission = $session->get('user/documentator/is_global_system_administrator');
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
                            <a href="/documentador/dashboard/spaces">Exit Administration</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#6A8EB2" style="padding-left: 12px; ">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_administration') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocHome">
                        <span><a style="<?php if ($menuSelectedCategory == 'doc_administration') echo 'color: black'; else echo 'color: white;' ?>" href="/documentador/administration" class="linkNoUnderline">Administration</a></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>
                    <?php if ($session->get('user/super_user_flag')): ?>

                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_configuration') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocConfiguration">
                            <span>Configuration</span>
                            <span class="<?php if ($menuSelectedCategory == 'doc_configuration') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                        <td width="8px"></td>
                    <?php endif ?>
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_spaces') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocSpaces">
                        <span>Spaces</span>
                        <span class="<?php if ($menuSelectedCategory == 'doc_spaces') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                        &nbsp;
                    </td>
                    <?php if ($hasDocumentadorGlobalAdministrationPermission || $hasDocumentadorGlobalSystemAdministrationPermission): ?>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_users') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocUsersSecurity">
                            <span>Users & Security</span>
                            <span class="<?php if ($menuSelectedCategory == 'doc_users') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                        <td width="8px"></td>
                    <?php endif ?>
                    <?php if ($session->get('user/super_user_flag')): ?>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_look_feel') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocLookFeel">
                            <span>Look & Feel</span>
                            <span class="<?php if ($menuSelectedCategory == 'doc_look_feel') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'doc_system') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminDocSystem">
                            <span>System</span>
                            <span class="<?php if ($menuSelectedCategory == 'doc_system') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                </tr>
            </table>
        </td>
        <td align="right">
            <input type="text" size="28" style="height: 15px; font-style: italic;" value="Administration Quick Search" name="search" />
        </td>
    </tr>
</table>

<div id="contentMenuAdminDocConfiguration" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/yongo/administration/projects">General Configuration</a></div>
            </td>
        </tr>
    </table>
</div>

<div id="contentMenuAdminDocSpaces" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/documentador/administration/spaces">All Spaces</a></div>
            </td>
        </tr>
    </table>
</div>

<div id="contentMenuAdminDocUsersSecurity" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td><div><a class="linkSubMenu" href="/documentador/administration/users">Users</a></div></td>
        </tr>
        <tr>
            <td><div><a class="linkSubMenu" href="/documentador/administration/groups">Groups</a></div></td>
        </tr>
        <?php if ($hasDocumentadorGlobalSystemAdministrationPermission): ?>
            <tr>
                <td>
                    <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                </td>
            </tr>
            <tr>
                <td><div><a class="linkSubMenu" href="/documentador/administration/global-permissions">Global Permissions</a></div></td>
            </tr>
        <?php endif ?>
    </table>
</div>

<div id="contentMenuAdminDocLookFeel" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/yongo/administration/issue-types">Themes</a></div>
            </td>
        </tr>
    </table>
</div>

<div id="contentMenuAdminDocSystem" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/yongo/administration/general-configuration">System Information</a></div>
            </td>
        </tr>
    </table>
</div>

<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />
<div style="display: none;" id="contentUserHome">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><?php echo LinkHelper::getUserProfileLink($loggedInUserId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, 'Profile', '', 'linkSubMenu') ?></div></td>
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