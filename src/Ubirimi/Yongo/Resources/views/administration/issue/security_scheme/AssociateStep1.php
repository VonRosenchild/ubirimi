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
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Associate Issue Security</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <form name="associate" method="post" action="/yongo/administration/project/associate-issue-security-scheme/<?php echo $projectId ?>">
            <table width="100%">
                <tr>
                    <td>Step 1 of 2: Select the scheme you wish to associate.</td>
                </tr>
                <tr>
                    <td>
                        <select class="inputTextCombo" name="scheme">
                            <?php while ($issueSecurityScheme = $issueSecuritySchemes->fetch_array(MYSQLI_ASSOC)): ?>
                                <option value="<?php echo $issueSecurityScheme['id'] ?>"><?php echo $issueSecurityScheme['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr size="1" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="next" class="btn ubirimi-btn">Next</button>
                        <button type="submit" name="cancel" class="btn ubirimi-btn">Cancel</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
