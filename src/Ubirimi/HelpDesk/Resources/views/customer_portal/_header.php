<?php
use Ubirimi\Container\UbirimiContainer;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery-ui.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery-ui-timepicker-addon.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.qtip.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/vendor/select2.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <script type="text/javascript" src="/js/vendor/jquery.iframe-transport.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fileupload.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/highcharts/highcharts.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/highcharts/exporting.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/bootstrap-dropdown.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <script type="text/javascript" src="/yongo/js/yongo.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/yongo_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/yongo_globals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/agile.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/agile_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/menu.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/ubirimi_admin_menu.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/upload_file.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/mousetrap.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/custom-dialog.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/helpdesk/js/help_desk.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/helpdesk/js/help_desk_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>" />
    <link rel="stylesheet" type="text/css" href="/css/menu.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/general.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery-ui.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery-ui-timepicker-addon.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/custom-dialog.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/yongo/css/upload_file.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/net.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.qtip.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fancybox.css?v=2.1.5" media="screen" />
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fancybox-buttons.css?v=1.0.5" media="screen" />

    <link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fileupload.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>

    <link rel="stylesheet" type="text/css" href="/css/print.css?<?php echo UbirimiContainer::get()['app.version'] ?>" media="print"/>

    <link rel="stylesheet" type="text/css" href="/helpdesk/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>" />

    <link rel="icon" type="image/ico" href="/img/logo_small.png" />
    <link rel="stylesheet" type="text/css" href="/yongo/css/jquery.contextMenu.css"/>

    <?php if (!isset($sectionPageTitle)) $sectionPageTitle = ''; ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
    </script>
    <title><?php echo $sectionPageTitle ?></title>

    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>