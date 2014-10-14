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
                    <div class="headerPageText"><?php echo $project['name'] ?> > <a href="/yongo/project/reports/<?php echo $projectId ?>">Reports</a> > Chart</div>
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
        <div class="headerPageText">Report: Pie Chart Report</div>
        <div>Description: A report showing the issues for a project or filter as a chart.</div>

        <?php require_once __DIR__ . '/_params.php' ?>

        <br />

        <?php if ($issuesAssignee): ?>
            <div id="chartContainer" style="height: 700px;"></div>
            <table class="table table-hover table-condensed" style="width: 600px;" align="center">
                <thead>
                    <tr>
                        <th>Assignee</th>
                        <th width="50px"># Issues</th>
                        <th width="50px">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($issuesAssignee as $assignee => $data): ?>
                        <tr>
                            <td><?php echo $data['assignee_name'] ?></td>
                            <td><a href="/yongo/issue/search?project=<?php echo $projectId ?>&assignee=<?php echo $assignee ?>"><?php echo $data['issues_count'] ?></a></td>
                            <td>
                                <?php
                                    $percentage = ($data['issues_count'] * 100) / $totalIssues;
                                    echo number_format($percentage, 2);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">No available data</div>
        <?php endif ?>

    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>
    <input type="hidden" id="statistic_type" value="<?php echo $statisticType ?>" name="statistic_type"/>
    <input type="hidden" id="chart_type" value="<?php echo $chartType ?>" name="statistic_type"/>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>