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
                        <a class="linkNoUnderline" href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a> > <?php echo $issueTypeScreenSchemeMetaData['name'] ?> > Configure
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <form name="edit_screen_metadata" action="/yongo/administration/screen/edit-scheme-issue-type-data/<?php echo $issueTypeScreenSchemeDataId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Issue Type</td>
                    <td>
                        <?php echo ucfirst($issueTypeScreenSchemeData['issue_type_name']) ?>
                    </td>
                </tr>
                <tr>
                    <td>Screen Scheme</td>
                    <td>
                        <select name="screen_scheme" class="select2InputSmall">
                            <?php while ($screenScheme = $screenSchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $screenScheme['id'] ?>"><?php echo $screenScheme['name'] ?></option>
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
                            <button type="submit" name="edit_issue_type_screen_scheme_data" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Issue Type Screen Scheme</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/screen/configure-scheme-issue-type/<?php echo $screenSchemeId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" value="<?php echo $issueTypeScreenSchemeData['issue_type_id'] ?>" name="issue_type" />
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>