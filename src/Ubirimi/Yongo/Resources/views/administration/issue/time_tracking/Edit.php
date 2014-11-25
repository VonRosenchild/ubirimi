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

require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Issue Features > Time Tracking > Update') ?>
    <div class="pageContent">

        <form method="post" name="edit_time_tracking" action="/yongo/administration/time-tracking/edit">
            <table width="100%">
                <tr>
                    <td width="140px" valign="top">Hours per day</td>
                    <td>
                        <input type="text" name="hours_per_day" value="<?php echo $session->get('yongo/settings/time_tracking_hours_per_day'); ?>" />
                        <div class="smallDescription">Please specify the number of hours per working day. The default for this value is 8 hours.</div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Days per week</td>
                    <td>
                        <input type="text" name="days_per_week" value="<?php echo $session->get('yongo/settings/time_tracking_days_per_week') ?>" />
                        <div class="smallDescription">Please specify the number of working days per week. The default for this value is 5 days.</div>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Default unit</td>
                    <td>
                        <select name="default_unit" class="inputTextCombo">
                            <option <?php if ($session->get('yongo/settings/time_tracking_default_unit') == 'w') echo 'selected="selected"' ?> value="w">Week</option>
                            <option <?php if ($session->get('yongo/settings/time_tracking_default_unit') == 'd') echo 'selected="selected"' ?> value="d">Day</option>
                            <option <?php if ($session->get('yongo/settings/time_tracking_default_unit') == 'h') echo 'selected="selected"' ?> value="h">Hour</option>
                            <option <?php if ($session->get('yongo/settings/time_tracking_default_unit') == 'm') echo 'selected="selected"' ?> value="m">Minute</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_time_tracking" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Time Tracking</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-features/time-tracking">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>