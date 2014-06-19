<?php require_once __DIR__ . '/_header.php'; ?>
<body>

    <?php require_once __DIR__ . '/_topMenu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">
                        Admin Home > Statistics
                    </div>
                </td>
            </tr>
        </table>

        <?php require_once __DIR__ . '/_menu.php' ?>

        <br />
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th><strong>Section</strong></th>
                <th><strong>Count</strong></th>
            </tr>
            </thead>
            <tr>
                <td width="200">Clients</td>
                <td align="right"><?php if ($clients) echo $clients->num_rows; else echo '0' ?></td>
            </tr>
            <tr>
                <td>Projects</td>
                <td align="right"><?php if ($projects) echo $projects->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Users</td>
                <td align="right"><?php if ($users) echo $users->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Issues</td>
                <td align="right"><?php if ($issues) echo $issues->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Spaces</td>
                <td align="right"><?php if ($spaces) echo $spaces->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Entities</td>
                <td align="right"><?php if ($entities) echo $entities->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Agile Boards</td>
                <td align="right"><?php if ($agileBoards) echo $agileBoards->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Agile Sprints</td>
                <td align="right"><?php if ($agileSprints) echo $agileSprints->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>SVN Repos</td>
                <td align="right"><?php if ($svnRepos) echo $svnRepos->num_rows; else echo '0'; ?></td>
            </tr>

            <tr>
                <td colspan="2"><br /><strong>TODAY</strong></td>
            </tr>
            <tr>
                <td width="200">Clients</td>
                <td align="right"><?php if ($clientsToday) echo $clientsToday->num_rows; else echo '0' ?></td>
            </tr>
            <tr>
                <td>Projects</td>
                <td align="right"><?php if ($projectsToday) echo $projectsToday->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Users</td>
                <td align="right"><?php if ($usersToday) echo $usersToday->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>Issues</td>
                <td align="right"><?php if ($issuesToday) echo $issuesToday->num_rows; else echo '0'; ?></td>
            </tr>
            <tr>
                <td>SVN Repos</td>
                <td align="right"><?php if ($svnReposToday) echo $svnReposToday->num_rows; else echo '0'; ?></td>
            </tr>
        </table>
    </div>
</body>