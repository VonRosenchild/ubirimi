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
use Ubirimi\Yongo\Repository\Screen\Screen;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/field-configurations">Field Configurations</a> >
                        <a class="linkNoUnderline" href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfiguration['id'] ?>"><?php echo $fieldConfiguration['name'] ?></a> >
                        Associate <?php echo $field['name'] ?> to screens
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="form_edit_field_configuration_screen" action="/yongo/administration/field-configuration/edit-screen/<?php echo $fieldConfigurationId ?>/<?php echo $fieldId ?>" method="post">

            <table class="table table-hover table-condensed">
                <tr>
                    <td width="400">Screen</td>
                    <th>Select</th>
                </tr>

                <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><?php echo $screen['name'] ?></td>
                        <td>
                            <?php $fieldInScreen = UbirimiContainer::get()['repository']->get(Screen::class)->checkFieldInScreen($clientId, $screen['id'], $fieldId); ?>
                            <input type="checkbox" <?php if ($fieldInScreen) echo 'checked="checked"' ?> name="field_screen_<?php echo $fieldId ?>_<?php echo $screen['id'] ?>" />
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
            <table width="100%">
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_field_configuration_screen" class="btn ubirimi-btn"><i class="icon-edit"></i> Update</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfigurationId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>