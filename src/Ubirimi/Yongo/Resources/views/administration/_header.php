<?php

/*
 *  Copyright (C) 2012-2014 SC Ubirimi SRL <info-copyright@ubirimi.com>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA.
 */

use Ubirimi\Container\UbirimiContainer;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="text/javascript" src="/js/vendor/jquery-1.11.1.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery-ui.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor//jscolor/jscolor.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/select2.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/general_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/custom-dialog.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/yongo.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/yongo_modals.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/yongo_shortcuts.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/ubirimi_admin_menu.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/mousetrap.min.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.iframe-transport.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.fileupload.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/jquery.jsPlumb-1.5.4.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/js/vendor/jquery.contextMenu.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/workflow_context.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>
    <script type="text/javascript" src="/yongo/js/workflow.js?<?php echo UbirimiContainer::get()['app.version'] ?>"></script>

    <link rel="stylesheet" type="text/css" href="/css/main.css?<?php echo UbirimiContainer::get()['app.version'] ?>" />
    <link rel="stylesheet" type="text/css" href="/css/vendor/jquery-ui.min.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/bootstrap.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/vendor/select2.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/menu.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/general.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/net.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/css/custom-dialog.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="stylesheet" type="text/css" href="/yongo/css/jquery.contextMenu.css?<?php echo UbirimiContainer::get()['app.version'] ?>"/>
    <link rel="icon" type="image/ico" href="/img/logo_small.png" />

    <?php if (!isset($sectionPageTitle)) $sectionPageTitle = ''; ?>
    <title><?php echo $sectionPageTitle ?></title>

    <?php if (UbirimiContainer::get()['deploy.on_demand']): ?>
        <?php require_once __DIR__ . '/../../../../Resources/views/_googleAnalytics.php' ?>
    <?php endif ?>
</head>