<?php
    use Ubirimi\Container\UbirimiContainer;

    $rootDomain = UbirimiContainer::get()['host.root_domain'];
?>

<div align="center">
    <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi <?php echo UbirimiContainer::get()['app.version'] ?> at <?php echo date('Y-m-d H:i:s') ?></span>
</div>