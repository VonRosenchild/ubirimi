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
<tr>
    <td colspan="<?php echo count($columns) + (count($columns) - 1) ?>">
        <div style="position: inherit; background-color: #f1f1f1; padding: 3px; border: 1px solid #acacac">
            <img border="0" src="/img/br_down.png" style="padding-bottom: 2px;" />
            <span>
                <a href="#"><?php echo $user['first_name'] . ' ' . $user['last_name'] . '</a> ' . $strategyIssue->num_rows . ' issues' ?>
            </span>
        </div>
    </td>
</tr>