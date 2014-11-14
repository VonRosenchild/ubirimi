<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\LinkHelper;
use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\IssueFilter;
use Ubirimi\Yongo\Repository\Permission\GlobalPermission;
use Ubirimi\Yongo\Repository\Permission\Permission;
use Ubirimi\Yongo\Repository\Project\YongoProject;

if (!strstr($_SERVER['REQUEST_URI'], '/yongo/issue')) {
    $session->remove('array_ids');
    $session->remove('last_search_parameters');
}

$projectsMenu = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_BROWSE_PROJECTS, 'array');

if ($projectsMenu) {
    $projectsForBrowsing = array();
    for ($i = 0; $i < count($projectsMenu); $i++)
        $projectsForBrowsing[$i] = $projectsMenu[$i]['id'];

    $filters = UbirimiContainer::get()['repository']->get(IssueFilter::class)->getAllByUser($loggedInUserId);

    if (null == $session->get('selected_project_id')) {
        if ($projectsMenu) {
            $session->set('selected_project_id', $projectsMenu[0]['id']);
        }
    }

    $selectedProjectId = $session->get('selected_project_id');
    $selectedProjectMenu = UbirimiContainer::get()['repository']->get(YongoProject::class)->getById($session->get('selected_project_id'));
}

$hasAdministerProjectsPermission = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_ADMINISTER_PROJECTS);
$hasCreateIssuePermission = false;
if (isset($projectsForBrowsing) && count($projectsForBrowsing)) {
    $hasCreateIssuePermission = UbirimiContainer::get()['repository']->get(YongoProject::class)->userHasPermission($projectsForBrowsing, Permission::PERM_CREATE_ISSUE, $loggedInUserId);
}

$styleSelectedMenu = 'style="background-color: #EEEEEE;';

$projectsWithCreatePermission = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProjectsByPermission($clientId, $loggedInUserId, Permission::PERM_CREATE_ISSUE);

if (!isset($menuSelectedCategory))
    $menuSelectedCategory = null;

$hasAdministrationPermission = $hasAdministerProjectsPermission || UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_ADMINISTRATORS) || UbirimiContainer::get()['repository']->get(UbirimiUser::class)->hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_YONGO_SYSTEM_ADMINISTRATORS);

Util::renderMaintenanceMessage();

if ($session->has('client/products')) {
    $clientProducts = $session->get('client/products');
} else {
    $clientProducts = UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getProducts(UbirimiContainer::get()['repository']->get(UbirimiClient::class)->getClientIdAnonymous(), 'array');
}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px">
        </td>
        <td style="height: 40px" valign="middle">
            <?php require_once __DIR__ . '/../../../Resources/views/productTopBar.php' ?>
        </td>
        <td style="padding-right: 12px;">
            <table align="right" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <?php if (Util::checkUserIsLoggedIn()): ?>
                        <td style="height:44px;" id="menu_top_user" width="58px" align="center" class="product-menu">
                            <span>
                                <img src="<?php echo UbirimiContainer::get()['repository']->get(UbirimiUser::class)->getUserAvatarPicture($session->get('user'), 'small') ?>" title="<?php echo $session->get('user/first_name') . ' ' . $session->get('user/last_name') ?>" height="33px" style="vertical-align: middle" />
                            </span>
                            <span class="arrow" style="top: 12px;"></span>
                            &nbsp;
                        </td>
                        <?php if ($hasAdministrationPermission): ?>
                            <td style="height:44px; border-left: 1px #9c9c9c solid;" width="170px" class="product-menu" align="center" valign="middle">
                                <div>
                                    <a href="/yongo/administration" title="Yongo Administration">
                                        Administration
                                    </a>
                                </div>
                            </td>
                        <?php endif ?>
                    <?php else: ?>
                        <td style="height:44px; border-left: 1px #9c9c9c solid;" width="100px" class="product-menu" align="center" valign="middle">
                            <div>
                                <a href="<?php echo Util::getHttpHost() ?>" title="Log In">Log In</a>
                            </div>
                        </td>
                    <?php endif ?>
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
                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'home') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuHome">
                        <span>Home</span>
                        <span class="<?php if ($menuSelectedCategory == 'home') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>
                    <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'project') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuProjects">
                        <span>Projects</span>
                        <span class="<?php if ($menuSelectedCategory == 'project') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>
                    <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'issue') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuIssues">
                        <span>Issues</span>
                        <span class="<?php if ($menuSelectedCategory == 'issue') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>
                    <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'filters') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuFilters">
                        <span>Filters</span>
                        <span class="<?php if ($menuSelectedCategory == 'filters') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                    <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_AGILE, $clientProducts)): ?>
                        <td width="8px"></td>
                        <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'agile') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuAgile">
                            <span>Agile</span>
                            <span class="<?php if ($menuSelectedCategory == 'agile') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                    <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_HELP_DESK, $clientProducts)): ?>
                        <td width="8px"></td>
                        <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'help_desk') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuHelpDesk">
                            <span>Help Desk</span>
                            <span class="<?php if ($menuSelectedCategory == 'help_desk') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                </tr>
            </table>
        </td>
        <td align="right" style="vertical-align: middle">
            <?php if ($hasCreateIssuePermission): ?>
                <input type="button" id="btnCreateIssue" value="Create Issue" />
            <?php endif ?>
            <?php if (isset($projectsForBrowsing)): ?>
                <input id="ubirimi_quick_search" type="text" style="height: 15px; font-style: italic;" value="Quick Search" name="search" />
                <input type="hidden" value="<?php echo implode('|', $projectsForBrowsing) ?>" id="projects_for_browsing" />
            <?php endif ?>
        </td>
    </tr>
</table>
<div id="topMessageBox" align="center" class="topMessageBox"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuIssues"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuProjects"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuFilters"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuHome"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuSVN"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuAgile"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuHelpDesk"></div>
<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />
<input type="hidden" value="<?php if ($hasAdministrationPermission) echo 1; else echo 0; ?>" id="has_administration_perm" />
<?php if (isset($selectedProjectId)): ?>
    <input type="hidden" value="<?php echo $session->get('selected_project_id') ?>" id="current_project_id" />
    <input type="hidden" value="<?php echo $selectedProjectMenu['code'] ?>" id="current_project_code" />
<?php endif ?>
<input type="hidden" value="<?php if ($projectsWithCreatePermission) echo "1"; else echo "0"; ?>" id="can_create_issue_in_projects" />

<div style="display: none;" id="contentUserHome">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td><div><?php echo LinkHelper::getUserProfileLink($loggedInUserId, SystemProduct::SYS_PRODUCT_YONGO, 'Profile', '', 'linkSubMenu') ?></div></td>
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
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<div class="ubirimiModalDialog" id="modalKeyboardShortcuts"></div>
<div class="ubirimiModalDialog" id="modalCreateIssue"></div>