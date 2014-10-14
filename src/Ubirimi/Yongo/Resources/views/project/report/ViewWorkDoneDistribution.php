<?php
require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > <a href="/yongo/project/reports/<?php echo $projectId ?>">Reports</a> > Work Done Distribution</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">
        <?php require_once __DIR__ . '/../_summaryMenu.php'; ?>

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
        <div class="headerPageText">Report: Work Done Distribution Report</div>
        <div>A report showing the issues for a project grouped by issue type and assignee.</div>
        <br />
        <div>
            <form name="from_work_distribution" method="post" action="/yongo/project/reports/<?php echo $projectId ?>/work-done-distribution/<?php echo $dateFrom ?>/<?php echo $dateTo ?>">
                <span>Interval</span>
                <span>From</span>
                <input type="text"
                       value="<?php echo $dateFrom ?>"
                       style="width: 100px"
                       class="filter-date-regular inputText"
                       name="filter_from_date" />
                <span>To</span>
                <input type="text"
                       value="<?php echo $dateTo ?>"
                       style="width: 100px"
                       class="filter-date-regular inputText"
                       name="filter_to_date" />
                <input type="submit" value="Show Report" class="btn ubirimi-btn" name="filter" />
            </form>
        </div>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Assiggne</th>
                    <?php foreach ($issueTypes as $issueType): ?>
                        <th><?php echo $issueType['name'] ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($workDataPrepared as $assignee => $work): ?>
                <tr>
                    <td><?php echo $assignee ?></td>
                    <?php foreach ($issueTypes as $issueType): ?>
                        <td><?php if (isset($work[$issueType['name']])) echo $work[$issueType['name']]; else echo '0'; ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>