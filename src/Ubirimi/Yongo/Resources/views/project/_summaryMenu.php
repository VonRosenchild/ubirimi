<?php
    if (!isset($month)) {
        $month = date('n');
    }

    if (!isset($year)) {
        $year = date('Y');
    }
?>

<ul class="nav nav-tabs" style="padding: 0px;">
    <li <?php if ($menuProjectCategory == 'summary'): ?>class="active"<?php endif ?>>
        <a href="/yongo/project/<?php echo $projectId ?>" title="Summary">Summary</a>
    </li>
    <li <?php if ($menuProjectCategory == 'issues'): ?>class="active" <?php endif ?>>
        <a href="/yongo/project/issues/<?php echo $projectId ?>" title="Issues">Issues</a></li>
    <li <?php if ($menuProjectCategory == 'calendar'): ?>class="active" <?php endif ?>>
        <a href="/yongo/project/calendar/<?php echo $projectId ?>/<?php echo $month ?>/<?php echo $year ?>" title="Calendar">Calendar</a>
    </li>
    <li <?php if ($menuProjectCategory == 'reports'): ?>class="active" <?php endif ?>>
        <a href="/yongo/project/reports/<?php echo $projectId ?>" title="Reports">Reports</a>
    </li>
    <li <?php if ($menuProjectCategory == 'versions'): ?>class="active" <?php endif ?>>
        <a href="/yongo/project/versions/<?php echo $projectId ?>" title="Versions">Versions</a>
    </li>
    <li <?php if ($menuProjectCategory == 'components'):?>class="active"<?php endif ?>>
        <a href="/yongo/project/components/<?php echo $projectId ?>" title="Components">Components</a>
    </li>
    <li <?php if ($menuProjectCategory == 'roles'):?>class="active"<?php endif ?>>
        <a href="/yongo/project/roles/<?php echo $projectId ?>" title="Roles">Roles</a>
    </li>
</ul>

