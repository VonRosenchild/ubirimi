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
use Ubirimi\Yongo\Repository\Issue\Issue;
use Ubirimi\Yongo\Repository\Issue\IssueSecurityScheme;
use Ubirimi\Yongo\Repository\Project\YongoProject;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText">
                        <a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Associate Issue Security
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <form name="associate" method="post"
              action="/yongo/administration/project/associate-issue-security-level/<?php echo $projectId ?>/<?php echo $schemeId ?>">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <div>Step 2 of 2: Associate any issues in this project that previously had their security level set,
                            with a security level from the new scheme.
                        </div>
                        <div>Selecting a new level will change the security level of all the affected issues to be the newly
                            selected security level.
                        </div>
                    </td>
                </tr>
                <?php
                    $header = '<tr>';
                        $header .= '<td>';
                            $header .= '<div><b>Security Levels for ';
                            if ($projectIssueSecurityScheme) $header .= $projectIssueSecurityScheme['name'] . '</b></div>';
                        $header .= '</td>';
                        $header .= '<td>';
                            $header .= '<div><b>Security Levels for ';
                            if ($selectedScheme) $header .= $selectedScheme['name'] . '</b></div>';
                        $header .= '</td>';
                    $header .= '</tr>';
                ?>

                <?php
                    $projectLevels = null;
                    if ($projectIssueSecuritySchemeId)
                        $projectLevels = UbirimiContainer::get()['repository']->get(IssueSecurityScheme::class)->getLevelsByIssueSecuritySchemeId($projectIssueSecuritySchemeId);

                    $issuesWithSecurityLevelSet = false;
                    if ($projectLevels) {
                        while ($projectLevel = $projectLevels->fetch_array(MYSQLI_ASSOC)) {
                            $issues = UbirimiContainer::get()['repository']->get(Issue::class)->getByParameters(array('project' => $projectId, 'security_scheme_level' => $projectLevel['id']));
                            if ($issues) {
                                $issuesWithSecurityLevelSet = true;
                                echo $header;
                                $header = '';
                                echo '<tr>';
                                    echo '<td>';
                                        echo $projectLevel['name'] . ' (' . $issues->num_rows . ' affected issues)';
                                    echo '</td>';
                                    echo '<td>';
                                        echo '<select name="new_level_' . $projectLevel['id'] . '" class="inputTextCombo">';
                                            echo '<option value="-1">None</option>';
                                            while ($level = $selectedSchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<option value="' . $level['id'] . '">' . $level['name'] . '</option>';
                                            }
                                            echo '</select>';
                                    echo '</td>';
                                echo '</tr>';
                                $selectedSchemeLevels->data_seek(0);
                            }
                        }

                        // deal with issues without an issue security level
                        $issues = UbirimiContainer::get()['repository']->get(YongoProject::class)->getIssuesWithNoSecurityScheme($projectId);

                        if ($issues) {
                            echo $header;
                            $header = '';
                            $issuesWithSecurityLevelSet = true;
                            echo '<tr>';
                                echo '<td>';
                                    echo 'None (' . $issues->num_rows . ' affected issues)';
                                echo '</td>';
                                echo '<td>';
                                    echo '<select name="no_level_set" class="inputTextCombo">';
                                    echo '<option value="-1">None</option>';
                                        while ($level = $selectedSchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                            echo '<option value="' . $level['id'] . '">' . $level['name'] . '</option>';
                                        }
                                    echo '</select>';
                                echo '</td>';
                            echo '</tr>';
                        }

                        if (!$issuesWithSecurityLevelSet) {
                            echo '<tr>';
                                echo '<td>';
                                    echo '<div>There are no previous secured issues.</div>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr>';
                            echo '<td>';
                                $issues = UbirimiContainer::getRepository(Issue::class)->getByParameters(array('project' => $projectId));
                                $countIssues = 0;
                                if ($issues) {
                                    $countIssues = $issues->num_rows;
                                }
                                echo '<div>None (' . $countIssues . ' affected issues)</div>';
                            echo '</td>';
                            echo '<td>';
                                echo '<div><b>Security Levels ' . $selectedScheme['name'] . '</b></div>';
                                echo '<div>';
                                    echo '<select name="new_level_0" class="inputTextCombo">';
                                        echo '<option value="-1">None</option>';
                                        while ($level = $selectedSchemeLevels->fetch_array(MYSQLI_ASSOC)) {
                                            echo '<option value="' . $level['id'] . '">' . $level['name'] . '</option>';
                                        }
                                    echo '</select>';
                                echo '</div>';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" name="associate" class="btn ubirimi-btn">Associate</button>
                        <button type="submit" name="cancel" class="btn ubirimi-btn">Cancel</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>