<ul class="nav nav-tabs" style="padding: 0px;">
    <li <?php if ($menuProjectCategory == 'queue'): ?>class="active"<?php endif ?>>
        <a href="/helpdesk/queues/<?php echo $projectId ?>/<?php if (isset($queueSelected)) echo $queueSelected['id']; else echo '-1'; ?>" title="Queues">Queues</a>
    </li>
    <li <?php if ($menuProjectCategory == 'reports'): ?>class="active"<?php endif ?>>
        <a href="/helpdesk/report/<?php echo $projectId ?>/<?php if (isset($slaSelected)) echo $slaSelected['id']; else echo '-1'; ?>" title="Reports">Reports</a>
    </li>
    <li <?php if ($menuProjectCategory == 'sla'): ?>class="active" <?php endif ?>>
        <a href="/helpdesk/sla/<?php echo $projectId ?>/<?php if (isset($slaSelected)) echo $slaSelected['id']; else echo '-1'; ?>" title="SLA">SLA</a>
    </li>
    <li <?php if ($menuProjectCategory == 'sla_calendar'): ?>class="active" <?php endif ?>>
        <a href="/helpdesk/sla/calendar/<?php echo $projectId ?>" title="SLA Calendars">SLA Calendars</a>
    </li>
    <li <?php if ($menuProjectCategory == 'customer_portal'): ?>class="active" <?php endif ?>>
        <a href="/helpdesk/customer-portal/administration/home/<?php echo $projectId ?>" title="Customer Portal">Customer Portal</a>
    </li>
</ul>