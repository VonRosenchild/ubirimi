<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

$session->set('selected_product_id', SystemProduct::SYS_PRODUCT_YONGO);

    $isSuperUser = $session->get('user/super_user_flag');

    $loggedInUserFirstName = $session->get('user/first_name');
    $loggedInUserLastName = $session->get('user/last_name');

    $hasGlobalAdministrationPermission = $session->get('user/yongo/is__global_administrator');
    $hasGlobalSystemAdministrationPermission = $session->get('user/yongo/is_global_system_administrator');
    $hasAdministerProjectsPermission = $session->get('user/yongo/is_global_project_administrator');

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
                    <td id="menu_top_userAdmin" width="58px" align="center" class="product-menu">
                        <span>
                            <img src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($session->get('user'), 'small') ?>" title="<?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>" height="33px" style="vertical-align: middle" />
                        </span>
                        <span class="arrow" style="top: 12px;"></span>
                        &nbsp;
                    </td>
                    <td style="height:44px; border-left: 1px #9c9c9c solid;" width="190px" class="product-menu" align="center" valign="middle">
                        <div>
                            <a href="/yongo/my-dashboard">Exit Administration</a>
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
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'administration') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminHome">
                        <span><a style="<?php if ($menuSelectedCategory == 'administration') echo 'color: black'; else echo 'color: white;' ?>" href="/yongo/administration" class="linkNoUnderline">Administration</a></span>
                        &nbsp;
                    </td>
                    <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission || $hasAdministerProjectsPermission): ?>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'project') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminProjects">
                            <span>Projects</span>
                            <span class="<?php if ($menuSelectedCategory == 'project') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                    <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'user') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminUsers">
                            <span>Users</span>
                            <span class="<?php if ($menuSelectedCategory == 'user') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'issue') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminIssues">
                            <span>Issues</span>
                            <span class="<?php if ($menuSelectedCategory == 'issue') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                        <td width="8px"></td>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'system') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAdminSystem">
                            <span>System</span>
                            <span class="<?php if ($menuSelectedCategory == 'system') echo 'arrowSelected'; else echo 'arrow' ?>" id="arrowProjects"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                </tr>
            </table>
        </td>
        <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission || $hasAdministerProjectsPermission): ?>
            <td align="right">
                <input type="text" size="28" style="height: 15px; font-style: italic;" value="Administration Quick Search" name="search" />
            </td>
        <?php endif ?>
    </tr>
</table>

<?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission || $hasAdministerProjectsPermission): ?>
    <div id="contentMenuAdminProjects" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission || $hasAdministerProjectsPermission): ?>
                <tr>
                    <td>
                        <div><a class="linkSubMenu" href="/yongo/administration/projects">All Projects</a></div>
                    </td>
                </tr>
            <?php endif ?>
            <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
                <tr>
                    <td>
                        <div><a class="linkSubMenu" href="/yongo/administration/project/categories">Project Categories</a></div>
                    </td>
                </tr>
            <?php endif ?>
            <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
                <tr>
                    <td>
                        <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><a class="linkSubMenu" href="/yongo/administration/project/add">Add Project</a></div>
                    </td>
                </tr>
            <?php endif ?>
        </table>
    </div>
<?php endif ?>

<?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
    <div id="contentMenuAdminUsers" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td><div><a class="linkSubMenu" href="/yongo/administration/users">Users</a></div></td>
            </tr>
            <tr>
                <td><div><a class="linkSubMenu" href="/yongo/administration/groups">Groups</a></div></td>
            </tr>
            <tr>
                <td><div><a class="linkSubMenu" href="/yongo/administration/roles">Roles</a></div></td>
            </tr>
            <tr>
                <td>
                    <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                </td>
            </tr>
            <tr>
                <td><div><a class="linkSubMenu" href="/yongo/administration/global-permissions">Global Permissions</a></div></td>
            </tr>
            <tr>
                <td><div><a class="linkSubMenu" href="/yongo/administration/user-preference">User Preferences</a></div></td>
            </tr>
        </table>
    </div>
<?php endif ?>

<?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
    <div id="contentMenuAdminIssues" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue-types">Issue Types...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/workflows">Workflows...</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/screens">Screens...</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/custom-fields">Fields...</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Issue Attributes</b>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue/statuses">Statuses</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue/resolutions">Resolutions</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue/priorities">Priorities</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/notification-schemes">Notification Schemes</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/permission-schemes">Permission Schemes</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a></div>
                </td>
            </tr>
        </table>
    </div>
<?php endif ?>

<?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
    <div id="contentMenuAdminSystem" style="display: none;">
        <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/general-configuration">General Configuration</a></div>
                </td>
            </tr>
            <?php if ($isSuperUser): ?>
                <?php if ($hasGlobalAdministrationPermission): ?>
                    <tr>
                        <td>
                            <div><a class="linkSubMenu" href="/yongo/administration/backup-manager">Backup Manager</a></div>
                        </td>
                    </tr>
                <tr>
                    <td>
                        <div><a class="linkSubMenu" href="/yongo/administration/import">Ubirimi Import</a></div>
                    </td>
                </tr>
                <?php endif ?>
            <?php endif ?>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/issue-features/time-tracking">Issue Features</a></div>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <div><a class="linkSubMenu" href="/yongo/administration/attachment-configuration">Advanced</a></div>
                </td>
            </tr>
        </table>
    </div>
<?php endif ?>

<div id="contentMenuUserMenu" style="display: none;">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><?php echo LinkHelper::getUserProfileLink($loggedInUserId, SystemProduct::SYS_PRODUCT_YONGO, 'Profile', '') ?></div></td>
        </tr>
        <tr>
            <td><div><a class="linkSubMenu" href="/yongo/issue/search">Issue Navigator</a></div></td>
        </tr>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr id="menu_keyboard_shortcuts">
            <td><div><a class="linkSubMenu" href="#">Keyboard Shortcuts</a></div></td>
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

<div class="ubirimiModalDialog" id="modalKeyboardShortcuts"></div>
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>

<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />