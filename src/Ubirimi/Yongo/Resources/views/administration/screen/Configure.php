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

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/screens">Screens</a> > ' . $screenMetadata['name'] . ' > Edit Screen Fields';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">
        <form name="add_priority" action="/yongo/administration/screen/configure/<?php echo $screenId ?>" method="post">

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td align="left">
                        <?php if ($source == 'project_screen'): ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/screens/<?php echo $projectId ?>">Go Back</a>
                        <?php elseif ($source == 'project_field'): ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/project/fields/<?php echo $projectId ?>">Go Back</a>
                        <?php else: ?>
                            <a class="btn ubirimi-btn" href="/yongo/administration/screens">Go Back</a>
                        <?php endif ?>
                    </td>
                    <td>
                        <a id="btnDeleteScreenField" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a>
                    </td>
                </tr>
            </table>

            <table width="100%">
                <tr>
                    <td>
                        <span>Available screen fields</span>
                        <select name="field" class="select2InputSmall">
                            <option value="-1">Select One Field</option>
                            <?php while ($field = $fields->fetch_array(MYSQLI_ASSOC)): ?>
                                <?php $found = false; ?>
                                <?php while ($screenData && $data = $screenData->fetch_array(MYSQLI_ASSOC)): ?>
                                    <?php if ($field['name'] == $data['field_name']): ?>
                                        <?php $found = true; ?>
                                    <?php endif ?>
                                <?php endwhile ?>
                                <?php if (!$found): ?>
                                    <option value="<?php echo $field['id'] ?>"><?php echo $field['name'] ?></option>
                                <?php endif ?>
                                <?php if ($screenData) $screenData->data_seek(0) ?>
                            <?php endwhile ?>
                        </select>
                        <input class="btn ubirimi-btn" type="submit" name="add_screen_field" value="Add field"/>
                    </td>
                </tr>
                <?php if ($screenData): ?>
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th></th>
                                <th width="80">Position</th>
                                <th>Name</th>
                                <th width="40" colspan="2">Order</th>
                            </tr>
                        </thead>

                        <?php $index = 0; ?>
                        <tbody>
                            <?php while ($data = $screenData->fetch_array(MYSQLI_ASSOC)): ?>
                                <?php $index++ ?>
                                <tr id="table_row_<?php echo $data['id'] ?>">
                                    <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $data['id'] ?>"/></td>
                                    <td width="80" align="left"><?php echo $data['position']; ?>.</td>
                                    <td width="200">
                                        <?php echo $data['field_name']; ?>
                                    </td>
                                    <td width="20" align="left">
                                        <?php if ($index != 1): ?>
                                            <a href="/yongo/administration/screen/configure/<?php echo $screenId ?>?position=<?php echo($index - 1) ?>&field_id=<?php echo $data['field_id'] ?>"><img src="/img/arrow_up_blue.gif"/></a>
                                        <?php endif ?>
                                    </td>

                                    <td align="left">
                                        <?php if ($index != $screenData->num_rows): ?>
                                            <a href="/yongo/administration/screen/configure/<?php echo $screenId ?>?position=<?php echo($index + 1) ?>&field_id=<?php echo $data['field_id'] ?>"><img src="/img/arrow_down_blue.gif"/></a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <tr>
                        <td>
                            <div class="messageGreen">There are no fields defined for this screen.</div>
                        </td>
                    </tr>
                <?php endif ?>

            </table>
        </form>
        <div id="deleteScreenFieldModal" class="ubirimiModalDialog"></div>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>