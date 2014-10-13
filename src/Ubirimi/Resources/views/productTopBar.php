<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\SystemProduct;
use Ubirimi\Util;
use Ubirimi\Repository\Client;

if ($session->has('client/products')) {
    $productsArray = $session->get('client/products');
} else {
    $productsArray = UbirimiContainer::get()['repository']->get('ubirimi.general.client')->getProducts(UbirimiContainer::get()['repository']->get('ubirimi.general.client')->getClientIdAnonymous(), 'array');
}

?>
<input type="hidden" value="<?php echo $session->get('selected_product_id') ?>" id="product_id" />
<table cellpadding="0" cellspacing="0" border="0" style="height: 44px">
    <tr>
        <?php ($session->get('selected_product_id') == -2) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>

        <td valign="middle" width="50px" align="center" class="product-menu" style="border-right: 1px #9c9c9c solid; color: #ffffff; <?php echo $style ?>">
            <a style="color: #ffffff; text-decoration: none;" href="/ubirimi/about"><img style="vertical-align: middle" src="/img/site/bg_white.png" height="30px"/></a>
        </td>

        <?php ($session->get('selected_product_id') == -1) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>
        <?php if (Util::checkUserIsLoggedIn()): ?>
            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="50px" class="product-menu" align="center">
                <a href="/general-settings/home"><img border="0" style="vertical-align: middle" height="30px" src="/img/settings.png"/></a>
            </td>
        <?php endif ?>
        <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_YONGO, $productsArray)): ?>
            <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>

            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="100px" class="product-menu" align="center" valign="middle">
                <div><a href="/yongo/my-dashboard">Yongo</a></div>
            </td>
        <?php endif ?>

        <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_SVN_HOSTING, $productsArray)): ?>
            <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_SVN_HOSTING) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>
            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="140px" class="product-menu" align="center">
                <div><a href="/svn-hosting/repositories">SVN Hosting</a></div>
            </td>
        <?php endif ?>

        <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $productsArray)): ?>
            <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_DOCUMENTADOR) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>
            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="180px" class="product-menu" align="center">
                <div><a href="/documentador/dashboard/spaces">Documentador</a></div>
            </td>
        <?php endif ?>

        <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_CALENDAR, $productsArray)): ?>
            <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_CALENDAR) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>
            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="110px" class="product-menu" align="center">
                <div><a href="/calendar/calendars">Events</a></div>
            </td>
        <?php endif ?>

        <?php if (Util::checkKeyAndValueInArray('sys_product_id', SystemProduct::SYS_PRODUCT_QUICK_NOTES, $productsArray)): ?>
            <?php ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_QUICK_NOTES) ? $style = 'background-color: #6A8EB2;' : $style = '' ?>
            <td style="<?php echo $style ?> border-right: 1px #9c9c9c solid;" width="130px" class="product-menu" align="center">
                <div><a href="/quick-notes/note/all">Quick Notes</a></div>
            </td>
        <?php endif ?>
    </tr>
</table>