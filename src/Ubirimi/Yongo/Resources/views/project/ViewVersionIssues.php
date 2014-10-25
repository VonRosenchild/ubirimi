<?php
use Ubirimi\LinkHelper;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <?php echo LinkHelper::getYongoProjectLink($projectId, $project['name']) ?> > <a class="linkNoUnderline" href="/yongo/project/versions/<?php echo $projectId ?>">Versions</a> > <?php echo $version['name'] ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/project/version/<?php echo $versionId ?>">Details</a></li>
            <li class="active"><a href="/yongo/project/version/issues/<?php echo $versionId ?>" title="Issues">Issues</a></li>
        </ul>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsPriority, $count, 'Unresolved by priority', 'priority', $projectId, null, $versionId, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsType, $count, 'Unresolved by type', 'type', $projectId, null, $versionId, '/yongo/issue/search'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsStatus, $count, 'Status summary', 'status', $projectId, null, $versionId, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsAssignee, $count, 'Unresolved by assignee', 'assignee', $projectId, null, $versionId, '/yongo/issue/search'); ?>
                </td>
            </tr>
        </table>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>