<?php
    use Ubirimi\Container\UbirimiContainer;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery-ui.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/custom-dialog.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/svn/js/menu.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/svn/js/svn.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/svn/js/svn_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/select2.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>" />
    <link rel="stylesheet" type="text/css" href="/css/menu.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/general.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery-ui.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/net.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/custom-dialog.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>

    <link rel="icon" type="image/ico" href="/img/logo_small.png" />
    <?php if (!isset($sectionPageTitle)) $sectionPageTitle = '' ?>
    <title><?php echo $sectionPageTitle ?></title>

    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>