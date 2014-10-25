<?php
use Ubirimi\LinkHelper;
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $title = LinkHelper::getYongoProjectLink($projectId, $project['name']) . ' > <a class="linkNoUnderline" href="/yongo/project/components/' . $projectId . '">Components</a> > ' . $component['name'];
        Util::renderBreadCrumb($title);
    ?>
    <div class="pageContent">
        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/project/component/<?php echo $componentId ?>">Details</a></li>
            <li class="active"><a href="/yongo/project/component/issues/<?php echo $componentId ?>" title="Issues">Issues</a></li>
        </ul>

        <table width="100%" cellpadding="8">
            <tr>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsPriority, $count, 'Unresolved by priority', 'priority', $projectId, $componentId, null, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsType, $count, 'Unresolved by type', 'type', $projectId, $componentId, null, '/yongo/issue/search'); ?>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsStatus, $count, 'Status summary', 'status', $projectId, $componentId, null, '/yongo/issue/search'); ?>
                </td>
                <td valign="top">
                    <?php Util::renderComponentStatSection($statsAssignee, $count, 'Unresolved by assignee', 'assignee', $projectId, $componentId, null, '/yongo/issue/search'); ?>
                </td>
            </tr>
        </table>
    </div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>