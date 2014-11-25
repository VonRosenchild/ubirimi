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

require_once __DIR__ . '/../_header.php'; ?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('GeneralSettings Configuration > Edit'); ?>
    <div class="pageContent">
        <form name="edit_configuration" method="post" action="/yongo/administration/general-configuration/edit">
            <table width="100%" cellspacing="0">
                <tr>
                    <td colspan="3" class="headerPageText">Options</td>
                </tr>
                <tr>
                    <td width="250" align="right">Allow unassigned issues</td>
                    <td width="10"></td>
                    <td>
                        <input <?php if ($clientSettings['allow_unassigned_issues_flag']) echo 'checked="checked"' ?> type="radio" name="allow_unassigned_issues" id="allow_unassigned_issues_ON" value="1">
                        <label for="allow_unassigned_issues_ON">ON</label>
                        <input <?php if (!$clientSettings['allow_unassigned_issues_flag']) echo 'checked="checked"' ?> type="radio" name="allow_unassigned_issues" id="allow_unassigned_issues_OFF" value="0">
                        <label for="allow_unassigned_issues_OFF">OFF</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><hr size="1" /></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="update_configuration" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Configuration</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/general-configuration">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>