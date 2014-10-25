<?php
use Ubirimi\LinkHelper;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';

?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = LinkHelper::getYongoProjectLink($projectId, $project['name']) .
                      ' > <a class="linkNoUnderline" href="/yongo/project/versions/' . $projectId .'">Versions</a> > ' .
                      $version['name'];
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/yongo/project/version/<?php echo $versionId ?>">Details</a></li>
            <li><a href="/yongo/project/version/issues/<?php echo $versionId ?>" title="Issues">Issues</a></li>
        </ul>

        <table>
            <tr>
                <td><span class="textLabel">Name:</span></td>
                <td><?php echo $version['name'] ?></td>
            </tr>
            <tr>
                <td><span class="textLabel">Description:</span></td>
                <td><?php echo $version['description'] ?></td>
            </tr>
        </table>

        <table width="100%" cellpadding="2">
            <tr>
                <td colspan="3" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Issues: Unresolved</span></td>
            </tr>
        </table>
        <?php if ($issues): ?>
            <?php
                $renderParameters = array('issues' => $issues, 'render_checkbox' => false, 'show_header' => true);
                $renderColumns = array('code', 'summary', 'priority', 'assignee', 'reporter', 'created');
                $issuesRendered = Util::renderIssueTables($renderParameters, $renderColumns, $clientSettings);
            ?>
            <?php if ($issuesResult[1] > 10): ?>
                <a href="/yongo/issue/search?project=<?php echo $projectId ?>&version=<?php echo $versionId ?>&resolution=-2">View All Issues</a>
            <?php endif ?>
        <?php else: ?>
            <div>None</div>
        <?php endif ?>

        <table width="100%" cellpadding="2">
            <tr>
                <td colspan="3" class="sectionDetail"><span class="sectionDetailSimple headerPageText">Issues: Update recently</span></td>
            </tr>
        </table>
        <?php if ($issuesUpdatedRecently): ?>
            <?php
                $renderParameters = array('issues' => $issuesUpdatedRecently, 'render_checkbox' => false, 'show_header' => true);
                $renderColumns = array('code', 'summary', 'priority', 'assignee', 'reporter', 'updated');
                $issuesRendered = Util::renderIssueTables($renderParameters, $renderColumns, $clientSettings);
            ?>
            <?php if ($issuesResultUpdatedRecently[1] > 10): ?>
                <a href="/yongo/issue/search?project=<?php echo $projectId ?>&version=<?php echo $versionId ?>&resolution=-2&sort=updated&order=desc">View All Issues</a>
            <?php endif ?>
        <?php else: ?>
            <div>None</div>
        <?php endif ?>

    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>