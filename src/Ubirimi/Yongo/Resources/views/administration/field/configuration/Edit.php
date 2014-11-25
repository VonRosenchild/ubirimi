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
use Ubirimi\Yongo\Repository\Field\FieldConfiguration;
use Ubirimi\Yongo\Repository\Screen\Screen;

require_once __DIR__ . '/../../_header.php';
?>
<body>
<?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/field-configurations">Field Configurations</a> > ' . $fieldConfiguration['name'] . ' > Configure';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($source == 'project'): ?>
                    <td><a href="/yongo/administration/project/<?php echo $projectId ?>" class="btn ubirimi-btn">Go Back</a></td>
                <?php else: ?>
                    <td><a href="/yongo/administration/field-configurations" class="btn ubirimi-btn">Go Back</a></td>
                <?php endif ?>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th width="600px"><b>Name</b></th>
                    <th align="left" width="450"><b>Screens</b></th>
                    <th align="left"><b>Operations</b></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($field = $allFields->fetch_array(MYSQLI_ASSOC)): ?>
                    <?php $data = UbirimiContainer::get()['repository']->get(FieldConfiguration::class)->getDataByConfigurationAndField($fieldConfigurationId, $field['id']) ?>
                    <tr>
                        <td>
                            <?php echo $field['name'] ?>
                            <?php if ($data['required_flag']): ?>
                                <span style="color: red;">Required</span>
                            <?php endif ?>
                            <?php if ($field['description']): ?>
                                <div class="smallDescription"><?php echo $field['description'] ?></div>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php $screens = UbirimiContainer::get()['repository']->get(Screen::class)->getByFieldId($clientId, $field['id']) ?>
                            <?php if ($screens): ?>
                                <ul>
                                    <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                                        <li><a href="/yongo/administration/screen/configure/<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></a></li>
                                    <?php endwhile ?>
                                </ul>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($data['visible_flag']): ?>
                                <span><a href="/yongo/administration/field-configuration/edit-metadata/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>">Edit</a></span>
                                <span>|</span>
                            <?php endif ?>
                            <?php if ($data['visible_flag']): ?>
                                <span><a href="/yongo/administration/field-configuration/edit-data/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>?visible_flag=0">Hide</a></span>
                            <?php else: ?>
                                <span><a href="/yongo/administration/field-configuration/edit-data/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>?visible_flag=1">Show</a></span>
                            <?php endif ?>
                            <?php if ($data['visible_flag']): ?>
                                <span>|</span>
                                <?php if ($data['required_flag']): ?>
                                    <span><a href="/yongo/administration/field-configuration/edit-data/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>?required_flag=0">Optional</a></span>
                                <?php else: ?>
                                    <span><a href="/yongo/administration/field-configuration/edit-data/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>?required_flag=1">Required</a></span>
                                <?php endif ?>
                                <span>|</span>
                                <span><a href="/yongo/administration/field-configuration/edit-screen/<?php echo $fieldConfigurationId ?>/<?php echo $field['id'] ?>">Screens</a></span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <input type="hidden" id="field_configuration" value="<?php echo $fieldConfiguration['id'] ?>" />
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>