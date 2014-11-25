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
    <div class="pageContent">
        <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/yongo/administration/issue-features/linking">Issue Linking</a> > Create Link Type') ?>
        <form name="add_link_type" action="/yongo/administration/link-type/add" method="post">
            
            <table width="100%">
                <tr>
                    <td width="200px" valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <div class="smallDescription">(eg "Duplicate")</div>
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($linkTypeDuplicateName): ?>
                            <div class="error">A link type with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Outward Link Description <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($outwardDescription)) echo $outwardDescription; ?>" name="outward" />
                        <div class="smallDescription">(eg "duplicates")</div>
                        <?php if ($emptyOutwardDescription): ?>
                            <div class="error">The outward description can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Inward Link Description <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($inwardDescription)) echo $inwardDescription; ?>" name="inward" />
                        <div class="smallDescription">(eg "is duplicated by")</div>
                        <?php if ($emptyInwardDescription): ?>
                            <div class="error">The inward name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="new_link_type" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Link Type</button>
                            <a class="btn ubirimi-btn" href="/yongo/administration/issue-features/linking">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>