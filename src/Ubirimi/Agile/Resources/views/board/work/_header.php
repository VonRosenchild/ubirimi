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

use Ubirimi\Agile\Repository\Board\Board;
use Ubirimi\Container\UbirimiContainer;
?>
<tr>
    <?php for ($i = 0; $i < count($columns); $i++): ?>
        <?php
            $statuses = UbirimiContainer::get()['repository']->get(Board::class)->getColumnStatuses($columns[$i]['id'], 'array');
            $textStatuses = '';
            for ($k = 0; $k < count($statuses); $k++) {
                $textStatuses .= $statuses[$k]['id'] . '_' . $statuses[$k]['name'] . '#';
            }
        ?>
        <td style="height: 20px; background-color: #CCCCCC" class="pageContentSmall2" align="center" width="<?php echo(100 / count($columns)) ?>%">
            <div class="headerPageText"><?php echo $columns[$i]['name'] ?></div>
            <input type="hidden" id="status_for_column_<?php echo $columns[$i]['id'] ?>" value="<?php echo $textStatuses ?>"/>
        </td>
        <?php if ($i != (count($columns) - 1)): ?>
            <td>
                <div>&nbsp;&nbsp;</div>
            </td>
        <?php endif ?>
    <?php endfor ?>
</tr>