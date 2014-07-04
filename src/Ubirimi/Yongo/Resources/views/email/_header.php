<?php
    use Ubirimi\Container\UbirimiContainer;

    $rootDomain = UbirimiContainer::get()['host.root_domain'];
?>

<table width="100%">
    <tr>
        <td width="40">
            <a href="<?php echo $rootDomain ?>"><img src="<?php echo $rootDomain ?>/img/small.yongo.png" border="0" /></a>
        </td>
    </tr>
</table>
<div>
    <img src="<?php echo $rootDomain ?>/img/bg.page.png" />
</div>