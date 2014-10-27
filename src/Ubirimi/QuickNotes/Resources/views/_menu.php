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

                    <td align="center" class="menuItemBasic <?php if ($menuSelectedCategory == 'notebooks') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuNotebooks">
                        <span>Notebooks</span>
                        <span class="<?php if ($menuSelectedCategory == 'notebooks') echo 'arrowSelected'; else echo 'arrow' ?>"></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>

                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'notes') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuNotes">
                        <span><a style="<?php if ($menuSelectedCategory == 'notes') echo 'color: black'; else echo 'color: white;' ?>" href="/quick-notes/note/snippets/all" class="linkNoUnderline">Notes</a></span>
                        &nbsp;
                    </td>
                    <td width="8px"></td>

                    <td class="menuItemBasic <?php if ($menuSelectedCategory == 'tags') echo 'menuItemSelected'; else echo 'menuItem' ?>" id="menuTags">
                        <span><a style="<?php if ($menuSelectedCategory == 'tags') echo 'color: black'; else echo 'color: white;' ?>" href="/quick-notes/tag/all" class="linkNoUnderline">Tags</a></span>
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
        <td align="right" style="vertical-align: middle">
            <input id="notebook_quick_search" type="text" style="height: 15px; font-style: italic;" value="Quick Search" name="search" />
        </td>
    </tr>
</table>

<div id="contentMenuNotebooks" style="display: none;">
    <table cellspacing="0" cellpadding="0" border="0" class="tableMenu" width="100%">
        <tr>
            <td>
                <div><a class="linkSubMenu" href="/quick-notes/my-notebooks">My Notebooks</a></div>
            </td>
        </tr>
        <tr>
            <td>
                <div><a class="linkSubMenu" href="#" id="btnCreateNotebook">Create Notebook</a></div>
            </td>
        </tr>

    </table>
</div>

<?php if ($loggedInUserId): ?>
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
<?php endif ?>

<input type="hidden" value="<?php echo $menuSelectedCategory ?>" id="menu_selected" />

<div class="ubirimiModalDialog" id="modalSendFeedback"></div>
<div class="ubirimiModalDialog" id="modalCreateNotebook"></div>
<div id="topMessageBox" align="center" class="topMessageBox"></div>