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
<div class="headerPageText"><?php echo $title ?></div>
<?php if ($issues): ?>
    <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <th width="10%">Key</th>
                <th width="60%">Summary</th>
                <th width="10%">Issue Type</th>
                <th width="10%">Priority</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><a href="/yongo/issue/<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . '-' . $issue['nr'] ?></a></td>
                    <td><?php echo $issue['summary'] ?></td>
                    <td><?php echo $issue['type_name'] ?></td>
                    <td><?php echo $issue['priority_name'] ?></td>
                    <td><?php echo $issue['status_name'] ?></td>
                </tr>
            <?php endwhile ?>
        </tbody>
    </table>
<?php else: ?>
    <div>There are no completed issues.</div>
<?php endif ?>