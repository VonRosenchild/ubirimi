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
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Field Configuration Schemes'); ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/custom-fields">Custom Fields</a></li>
            <li><a href="/yongo/administration/field-configurations">Field Configurations</a></li>
            <li class="active"><a href="/yongo/administration/field-configurations/schemes">Field Configuration Schemes</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/field-configuration/add-scheme" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Field Configuration Scheme</a></td>
                <?php if ($fieldConfigurationSchemes): ?>
                    <td><a id="btnEditFieldConfigurationSchemeMetadata" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnEditFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-wrench"></i> Configure</a></td>
                    <td><a id="btnCopyFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled">Copy</a></td>
                    <td><a id="btnDeleteFieldConfigurationScheme" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>
        <div class="infoBox">
            Field Configuration Schemes map <a href="/yongo/administration/field-configurations">Field Configurations</a> to issue types. A Field Configuration Scheme can be associated with one or more projects, making issues in these projects use the Field Configuration mapped to their issue type.
        </div>
        <?php if ($fieldConfigurationSchemes): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Projects</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($fieldConfigurationScheme = $fieldConfigurationSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $fieldConfigurationScheme['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $fieldConfigurationScheme['id'] ?>" />
                        </td>
                        <td>
                            <a href="/yongo/administration/field-configuration/scheme/edit/<?php echo $fieldConfigurationScheme['id'] ?>"><?php echo $fieldConfigurationScheme['name'] ?></a>
                            <div class="smallDescription"><?php echo $fieldConfigurationScheme['description'] ?></div>
                        </td>
                        <td width="500px">
                            <?php $projects = UbirimiContainer::get()['repository']->get(YongoProject::class)->getByIssueTypeFieldConfigurationScheme($clientId, $fieldConfigurationScheme['id']) ?>
                            <?php if ($projects): ?>
                                <ul>
                                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                    <li><a href="/yongo/administration/project/<?php echo $project['id'] ?>"><?php echo $project['name'] ?></a></li>
                                <?php endwhile ?>
                                </ul>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfigurationScheme['id'] ?>" value="0" />
                            <?php else: ?>
                                <input type="hidden" id="delete_possible_<?php echo $fieldConfigurationScheme['id'] ?>" value="1" />
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div id="deleteIssueSetting"></div>
            <input type="hidden" value="type" id="setting_type" />
        <?php else: ?>
            <div class="messageGreen">There are no field configuration schemes defined.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteFieldConfigurationScheme"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>