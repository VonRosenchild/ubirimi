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

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php Util::renderBreadCrumb('Administration') ?>

    <div class="pageContent">
        <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission || $hasYongoAdministerProjectsPermission): ?>
            <table width="100%">
                <tr>
                    <td width="40%">
                        <table width="100%">
                            <tr>
                                <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Projects (<?php echo $countProjects ?>)</span></td>
                            </tr>
                            <?php if ($last5Projects): ?>
                            <tr>
                                <td>
                                    <div>Last 5 Projects</div>
                                </td>
                            </tr>
                            <tr>
                                <table cellspacing="0px" cellpadding="0px">
                                    <tr>
                                        <?php while ($project = $last5Projects->fetch_array(MYSQLI_ASSOC)): ?>
                                            <td>
                                                <img src="/img/project.png" height="48px"/>

                                                <div>
                                                    <a href="/yongo/administration/project/<?php echo $project['id'] ?>"><?php echo $project['name'] ?></a>
                                                </div>
                                            </td>
                                            <td width="10px"></td>
                                        <?php endwhile ?>
                                    </tr>
                                </table>

                            </tr>
                            <?php endif ?>
                            <tr>
                                <td>
                                    <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission || $hasYongoAdministerProjectsPermission): ?>
                                        <a href="/yongo/administration/projects">All Projects</a>
                                    <?php endif ?>
                                    <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission): ?>
                                        <b>&middot;</b>
                                        <a href="/yongo/administration/project/categories">Project Categories</a>
                                    <?php endif ?>
                                    <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission): ?>
                                        <b>&middot;</b>
                                        <a href="/yongo/administration/project/add">Add Project</a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission): ?>
                    <tr>
                        <td valign="top" align="left" colspan="2">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Users</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="/yongo/administration/users">Users</a>
                                        <b>&middot;</b>
                                        <a href="/yongo/administration/groups">Groups</a>
                                        <b>&middot;</b>
                                        <a href="/yongo/administration/roles">Roles</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php endif ?>
            </table>
            <?php if ($hasYongoGlobalAdministrationPermission || $hasYongoGlobalSystemAdministrationPermission): ?>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" align="left" width="50%">
                        <table width="100%">
                            <tr>
                                <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Issues</span></td>
                            </tr>
                            <tr>
                                <td>Issue Types...</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/issue-types">Issue Types</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Workflows...</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/workflows">Workflows</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/workflows/schemes">Workflow Schemes</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Screens...</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/screens">Screens</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/screens/schemes">Screen Schemes</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/screens/issue-types">Issue Type Screen Schemes</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Fields...</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/field-configurations">Field Configurations</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/field-configurations/schemes">Field Configuration
                                        Schemes</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Issue Attributes</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/issue/statuses">Statuses</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/issue/resolutions">Resolutions</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/issue/priorities">Priorities</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/notification-schemes">Notification Schemes</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/permission-schemes">Permission Schemes</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/issue-security-schemes">Issue Security Schemes</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" align="left" width="50%">
                        <table width="100%">
                            <tr>
                                <td colspan="2" class="sectionDetail"><span class="sectionDetailSimple headerPageText">System</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/general-configuration">General Configuration</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Issue Features</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/issue-features/time-tracking">Time Tracking</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/issue-features/linking">Issue Linking</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Advanced</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yongo/administration/attachment-configuration">Attachments</a>
                                    <b>&middot;</b>
                                    <a href="/yongo/administration/events">Events</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">Unauthorized access. Please contact the system administrator.</div>
        <?php endif ?>

    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>