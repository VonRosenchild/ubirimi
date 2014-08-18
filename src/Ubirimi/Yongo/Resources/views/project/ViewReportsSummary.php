<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

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

        <?php
            $menuProjectCategory = 'reports';
            require_once __DIR__ . '/_summaryMenu.php';
        ?>

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
            <tbody>
                <tr>
                    <td valign="top">
                        <div class="headerPageText"><a href="/yongo/project/reports/<?php echo $projectId ?>/chart-report">Chart Report</a></div>
                        <div>A report showing the issues for a project or filter as a pie chart.</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id"/>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>