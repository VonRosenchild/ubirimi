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
                    <div class="headerPageText"><?php echo $project['name'] ?> > <a href="/yongo/project/reports/<?php echo $projectId ?>">Reports</a> > Chart</div>
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

        <?php require_once __DIR__ . '/_params.php' ?>
        datele
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>