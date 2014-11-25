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

?>
<table id="parent_issue_possible_transitions">
    <tr>
        <td>
            <span>All sub-tasks for parent issue are now Done.</span>
            <br />
            <span>To update this parent issue to match, select a transition:</span>
        </td>
    </tr>
    <tr>
        <td>
            <?php foreach ($dataValues as $transition): ?>
                <?php $id = 'parent_possible_transition_'
                        . $transition['screen']
                        . '_' . $transition['step_id_from']
                        . '_' . $transition['step_id_to']
                        . '_' . $transition['workflow_id']
                        . '_' . $transition['project_id'];
                ?>
            <input <?php echo $textSelected ?> id="<?php echo $id ?>" type="radio" name="transitions_for_parent_issue" value="<?php echo $transition['transition_name'] ?>" />
            <label for="<?php echo $id ?>"><?php echo $transition['transition_name'] ?></label>
            <br />
            <?php $textSelected = ''; ?>
            <?php endforeach ?>
        </td>
    </tr>
</table>