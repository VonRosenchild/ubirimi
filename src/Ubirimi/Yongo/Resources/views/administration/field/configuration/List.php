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
use Ubirimi\Yongo\Repository\Field\FieldConfigurationScheme;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">Field Configurations</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/custom-fields">Custom Fields</a></li>
            <li class="active"><a href="/yongo/administration/field-configurations">Field Configurations</a></li>
            <li><a href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/field-configuration/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Field Configuration</a></td>
                <?php if ($fieldConfigurations): ?>
                    <td><a id="btnEditFieldConfigurationMetadata" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnEditFieldConfiguration" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                    <td><a id="btnCopyFieldConfiguration" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteFieldConfiguration" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>
        <div class="infoBox">
            A Field Configuration provides the ability to change field behavior, it essentially tells Ubirimi how to handle a particular field. For example, a Field Configuration can be used to hide a field from all input screens and views, or to make a field require a value every time it is edited.
        </div>
        <?php if ($fieldConfigurations): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Field Configuration Schemes</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($fieldConfiguration = $fieldConfigurations->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $fieldConfiguration['id'] ?>">
                        <td width="22" >
                            <input type="checkbox" value="1" id="el_check_<?php echo $fieldConfiguration['id'] ?>" />
                        </td>
                        <td>
                            <a href="/yongo/administration/field-configuration/edit/<?php echo $fieldConfiguration['id'] ?>"><?php echo $fieldConfiguration['name'] ?></a>
                            <div class="smallDescription"><?php echo $fieldConfiguration['description'] ?></div>
                        </td>
                        <td width="500px">
                            <?php $schemes = UbirimiContainer::get()['repository']->get(FieldConfigurationScheme::class)->getFieldConfigurationsSchemesByFieldConfigurationId($clientId, $fieldConfiguration['id']) ?>
                            <?php if ($schemes): ?>
                                <ul>
                                    <?php while ($scheme = $schemes->fetch_array(MYSQLI_ASSOC)): ?>
                                        <li><a href="/yongo/administration/field-configuration/scheme/edit/<?php echo $scheme['id'] ?>"><?php echo $scheme['name'] ?></a></li>
                                    <?php endwhile ?>
                                </ul>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfiguration['id'] ?>" value="0" />
                            <?php else: ?>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfiguration['id'] ?>" value="1" />
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div id="deleteIssueSetting"></div>
            <input type="hidden" value="type" id="setting_type" />
        <?php else: ?>
            <div class="messageGreen">There are no field configurations defined.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteFieldConfiguration"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>