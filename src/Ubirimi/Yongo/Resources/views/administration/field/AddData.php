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

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/custom-fields">Custom Fields</a> > Create Custom Field - Details (Step 2 of 2)') ?>
    <div class="pageContent">
        <form name="add_custom_field" action="/yongo/administration/custom-field/add-data/<?php echo $fieldTypeCode ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input type="text" value="" name="name" class="inputText" />
                        <?php if ($emptyName): ?>
                            <br />
                            <div class="error">The name of the field can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <br />
                            <div class="error">Duplicate field name. Please choose another name.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"></textarea></td>
                </tr>
                <tr>
                    <td colspan="2">Choose applicable issue types</td>
                </tr>
                <tr>
                    <td valign="top">Issue Types</td>
                    <td>
                        <select name="issue_type[]" class="inputTextCombo" size="9" multiple="multiple">
                            <option selected="selected" value="-1">Any Issue Types</option>
                            <?php while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $issueType['id'] ?>"><?php echo $issueType['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Choose applicable context</td>
                </tr>
                <tr>
                    <td valign="top">Projects</td>
                    <td>
                        <select name="project[]" class="inputTextCombo" size="9" multiple="multiple">
                            <option selected="selected" value="-1">Any Project</option>
                            <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
                            <?php endwhile ?>
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
                            <button type="submit" name="finish_custom_field" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Custom Field</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/custom-fields">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>