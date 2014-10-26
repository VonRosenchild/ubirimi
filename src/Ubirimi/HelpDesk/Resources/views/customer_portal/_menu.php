<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Repository\User\UbirimiUser;
use Ubirimi\SystemProduct;

$styleSelectedMenu = 'style="background-color: #EEEEEE;';
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#003466">
    <tr>
        <td width="12px">
        </td>
        <td style="height: 40px" valign="middle">

            <table cellpadding="0" cellspacing="0" border="0" style="height: 44px">
                <tr>
                    <?php ($session->get('selected_product_id') == -2) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>

                    <td valign="middle" width="50px" align="center" class="product-menu" style="border-right: 1px #9c9c9c solid; color: #ffffff; <?php echo $style ?>">
                        <a style="color: #ffffff; text-decoration: none;" href="/helpdesk/customer-portal/about">
                            <img style="vertical-align: middle" src="/img/site/bg_white.png" height="30px"/>
                        </a>
                    </td>

                    <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_HELP_DESK) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>

                    <td style="border-right: 1px #9c9c9c solid; <?php echo $style ?>" width="100px" class="product-menu" align="center" valign="middle">
                        <div><a href="/helpdesk/customer-portal/dashboard">Helpdesk</a></div>
                    </td>
                </tr>
            </table>
        </td>
        <td style="padding-right: 12px;">
            <table align="right" border="0" cellpadding="0" cellspacing="0">
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
                    <?php if ($menuSelectedCategory == 'ubirimi_about'): ?>
                        <td class="menuItemBasic menuItemSelected">
                            <span><a class="linkNoUnderline" style="color: black" href="/helpdesk/customer-portal/about">About</a></span>
                        </td>
                    <?php else: ?>
                        <td class="menuItemBasic <?php if ($menuSelectedCategory == 'home') echo 'menuItemSelected'; else echo 'menuItem' ?>">
                            <span><a class="linkNoUnderline" style="<?php if ($menuSelectedCategory == 'home') echo 'color: black'; else echo 'color: white;' ?>" href="/helpdesk/customer-portal/dashboard">Dashboard</a></span>
                        </td>
                        <td width="8px"></td>
                        <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'project') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuProjects">
                            <span>Projects</span>
                            <span class="<?php if ($menuSelectedCategory == 'project') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                            &nbsp;
                        </td>
                    <?php endif ?>
                </tr>
            </table>
        </td>
        <?php if ($session->has('selected_project_id')): ?>
            <td align="right" style="vertical-align: middle">
                <input type="button" id="btnCreateIssue" value="Create Ticket" />
                <input id="ubirimi_quick_search" type="text" style="height: 15px; margin-right: 12px; font-style: italic;" value="Quick Search" name="search" />
            </td>
        <?php endif ?>
    </tr>
</table>
<div id="topMessageBox" align="center" class="topMessageBox"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuProjects"></div>
<div style="border-left: 1px solid #c0c0c0;" id="contentMenuHome"></div>

<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />

<div style="display: none;" id="contentUserHome">
    <table class="tableMenu" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/helpdesk/customer-portal/profile/<?php echo $loggedInUserId ?>">Profile</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td><div><a class="linkSubMenu" href="/helpdesk/customer-portal/sign-out">Sign out</a></div></td>
        </tr>
    </table>
</div>
<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<div class="ubirimiModalDialog" id="modalCreateIssue"></div>

<input type="hidden" id="can_create_issue_in_projects" value="1" />