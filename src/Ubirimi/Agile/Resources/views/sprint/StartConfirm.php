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

?>
<table>
    <tr>
        <td>Sprint Name</td>
        <td>
            <input type="text" value="<?php echo $sprint['name'] ?>" class="inputText" id="sprint_name" />
        </td>
    </tr>
    <tr>
        <td valign="top">Start Date</td>
        <td>
            <input value="<?php echo $today ?>" type="text" class="inputText" id="sprint_start_date" style="width: 100px" />
            <div class="error" id="invalid_start_sprint_date"></div>
        </td>
    </tr>
    <tr>
        <td valign="top">End Date</td>
        <td>
            <input value="<?php echo $todayPlus2Weeks ?>" type="text" class="inputText" id="sprint_end_date" style="width: 100px" />
            <div class="error" id="invalid_end_sprint_date"></div>
        </td>
    </tr>
</table>