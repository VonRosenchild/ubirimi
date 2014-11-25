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

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a class="linkNoUnderline" href="/yongo/administration/screens/schemes">Screen Schemes</a> > <?php echo $screenSchemeMetaData['name'] ?> > Configure
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="edit_screen_metadata" action="/yongo/administration/screen/edit-scheme-data/<?php echo $screenSchemeDataId ?>" method="post">

            <table width="100%">
                <tr>
                    <td width="100" valign="top">Operation</td>
                    <td>
                        <?php echo ucfirst($screenSchemeData['operation_name']) . ' Issue' ?>
                    </td>
                </tr>
                <tr>
                    <td>Screen</td>
                    <td>
                        <select name="screen" class="select2InputSmall">
                            <?php while ($screen = $screens->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($selectedScreenId == $screen['id']): ?>selected="selected"<?php endif ?> value="<?php echo $screen['id'] ?>"><?php echo $screen['name'] ?></option>
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
                        <button type="submit" name="edit_screen_scheme" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Screen Scheme</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/screen/configure-scheme/<?php echo $screenSchemeId ?>">Cancel</a>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $operationId ?>" name="operation" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>