<?php
    require_once __DIR__ . '/../../_header.php';
    $menuProjectCategory = 'reports';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Issues</div>
                </td>
            </tr>
        </table>

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

        <form name="chart_report" action="/yongo/project/reports/<?php echo $projectId ?>/chart-report" method="post">
            <table width="100%" cellpadding="4px">
                <tbody>
                    <tr>
                        <td width="200px" valign="top" align="right">
                            <div>Project or saved filter</div>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top">
                            <div>Statistic Type</div>
                        </td>
                        <td valign="top">
                            <select name="statistic_type" class="select2InputSmall">
                                <option value="assignee">Assignee</option>
                            </select>
                            <div>Select which type of statistic to display for this filter</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr size="1" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" name="show_report" class="btn ubirimi-btn">Show Report</button>
                            <a class="btn ubirimi-btn" href="/documentador/administration/groups">Cancel</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>