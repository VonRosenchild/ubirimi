<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>

    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px"/>
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Summary</div>
                </td>
                <td align="right">
                    <?php require_once __DIR__ . '/_main_actions.php' ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="pageContent">

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/project/<?php echo $projectId ?>">Summary</a></li>
            <li><a href="/yongo/administration/project/issue-types/<?php echo $projectId ?>">Issue Types</a></li>
            <li><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Workflows</a></li>
            <li><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li class="active"><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <div class="separationVertical"></div>
        <?php if ($project['help_desk_enabled_flag']): ?>
            <div>This project is using Helpdesk!</div>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" width="50%">
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/helpdesk/queues/<?php echo $projectId ?>/<?php if (isset($queueSelected)) echo $queueSelected['id']; else echo '-1'; ?>" title="Queues">Queues</a>
                        </div>
                        <div>A shared view of tickets for your team to work from.</div>
                    </td>
                    <td width="10px"></td>
                    <td valign="top">
                        <div class="headerPageText">
                            <a href="/helpdesk/sla/<?php echo $projectId ?>/<?php if (isset($slaSelected)) echo $slaSelected['id']; else echo '-1'; ?>" title="SLA">SLA</a>
                        </div>

                        <div>Prioritize work for your team with SLA metrics and time targets.</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <br />
                        <br />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div class="headerPageText">
                            <a href="/helpdesk/customer-portal/administration/home/<?php echo $projectId ?>" title="Customer Portal">Customer Portal</a>
                        </div>
                        <div>An easy way for your customers to raise requests and interact with your team.</div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <br />
            <hr size="1" />
            <br />
            <div>
                <div class="headerPageText">Disable this helpdesk</div>
                <div>If you disable this service desk, your queues, reports, SLAs and customer portal will no longer be accessible. Issues created in the project and any linked knowledge bases are not affected.
                If you change your mind, you can use this page to restore the service desk. It will be restored with the configuration settings it had when you disabled it.</div>
                <a href="/yongo/administration/project/helpdesk/toggle/<?php echo $projectId ?>" class="btn ubirimi-btn">Disable</a>
            </div>
        <?php else: ?>
            <div>This project is not using Helpdesk!</div>
            <a href="/yongo/administration/project/helpdesk/toggle/<?php echo $projectId ?>" class="btn ubirimi-btn">Enable Helpdesk</a>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>