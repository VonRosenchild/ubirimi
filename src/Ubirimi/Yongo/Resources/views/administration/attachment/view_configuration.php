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

use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Attachments') ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/administration/attachment-configuration">Attachments</a></li>
            <li><a href="/yongo/administration/events">Events</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/edit-attachment-configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Settings</a></td>
            </tr>
        </table>
        <table class="table table-hover table-condensed">
            <tbody>
                <tr>
                    <td width="30%">Allow Attachments</td>
                    <td><?php if ($settings['allow_attachments_flag']) echo 'Yes'; else echo 'No' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>