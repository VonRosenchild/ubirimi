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
    <?php Util::renderBreadCrumb('<a href="/yongo/administration/custom-fields">Custom Fields</a> > ' . $field['name'] . ' > Define Custom Values') ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/custom-field/value/add/<?php echo $field['id'] ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Custom Value</a></td>
                <td><a id="btnEditCustomFieldValue" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteCustomFieldValue" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <?php if ($fieldData): ?>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th>Value</th>
                </tr>
                </thead>

                <?php while ($fieldValue = $fieldData->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $fieldValue['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $fieldValue['id'] ?>" />
                        </td>
                        <td>
                            <?php echo $fieldValue['value']; ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no custom field values defined.</div>
        <?php endif ?>
        <div class="ubirimiModalDialog" id="modalDeleteCustomFieldValue"></div>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>