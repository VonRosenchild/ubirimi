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
    <?php
        $breadCrumb = '<a class="linkNoUnderline" href="/yongo/administration/events">Events</a> > ' . $event['name'] . ' > Update';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <form name="add_event" action="/yongo/administration/edit-event/<?php echo $eventId ?>" method="post">
            <table width="100%">
                <tr>
                    <td width="150" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $event['name']; ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The event name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php echo $event['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <button type="submit" name="edit_event" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Event</button>
                        <a class="btn ubirimi-btn" href="/yongo/administration/events">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>