<?php use Ubirimi\Container\UbirimiContainer; ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <meta name="robots" content="noindex">

    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/general.css">
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="icon" type="image/ico" href="/img/site/bg.logo.png" />

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="/js/general.js"></script>
    <script type="text/javascript" src="/js/vendor/select2.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <!--[if lt IE 9]>
    <script>window.html5 || document.write('<script src="/js/vendor/html5shiv.js"><\/script>')</script>
    <![endif]-->

    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>
