<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\SystemProduct;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="min-height:100% !important;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery-ui.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/bootstrap-dropdown.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/time_picker.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.qtip.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/vendor/select2.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.iframe-transport.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fileupload.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/ckeditor/ckeditor.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/quick_notes/js/quick_notes.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/quick_notes/js/quick_notes_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/general-settings/js/menu_general.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/custom-dialog.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>" />
    <link rel="stylesheet" type="text/css" href="/css/menu.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/general.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery-ui.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/custom-dialog.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/time_picker.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/net.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.qtip.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/quick_notes/css/quick_notes.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery.fileupload.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/print.css?<?php echo UbirimiContainer::get()['app.version'] ?>" media="print"/>

    <link rel="icon" type="image/ico" href="/img/logo_small.png" />
    <link rel="stylesheet" type="text/css" href="/yongo/css/jquery.contextMenu.css"/>

    <?php if (!isset($sectionPageTitle)) $sectionPageTitle = ''; ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php if ($session->get('selected_product_id') == SystemProduct::SYS_PRODUCT_YONGO): ?>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
        </script>
    <?php endif ?>
    <title><?php echo $sectionPageTitle ?></title>

    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>