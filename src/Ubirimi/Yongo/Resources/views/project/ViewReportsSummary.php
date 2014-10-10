<?php
    $menuProjectCategory = 'reports';

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Reports</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <?php require_once __DIR__ . '/_summaryMenu.php'; ?>

        <?php if ($hasAdministerProject): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td>
                        <a id="btnNew" href="/yongo/administration/project/<?php echo $projectId ?>" class="btn ubirimi-btn">Administer Project</a>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div class="separationVertical"></div>
        <?php endif ?>

        <table width="100%" cellpadding="8" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody class="no-underline">
                <tr>
                    <td valign="top">
                        <div><a href="/yongo/project/reports/<?php echo $projectId ?>/chart-report/assignee/column">Chart Report</a></div>
                    </td>
                    <td><div>A report showing the issues for a project or filter as a pie chart.</div></td>
                </tr>
                <tr>
                    <td valign="top">
                        <div><a href="/yongo/project/reports/<?php echo $projectId ?>/work-done-distribution/<?php echo $dateFrom ?>/<?php echo $dateTo ?>">Work done distribution</a></div>
                    </td>
                    <td>
                        <div>A report showing the issues for a project grouped by issue type and assignee.</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>