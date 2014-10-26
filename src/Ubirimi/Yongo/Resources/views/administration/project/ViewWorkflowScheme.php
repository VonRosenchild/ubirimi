<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Yongo\Repository\Issue\IssueTypeScheme;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><a href="/yongo/administration/projects" class="linkNoUnderline">Projects</a> > <?php echo $project['name'] ?> > Workflows</div>
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
            <li class="active"><a href="/yongo/administration/project/workflows/<?php echo $projectId ?>">Workflows</a></li>
            <li><a href="/yongo/administration/project/screens/<?php echo $projectId ?>">Screens</a></li>
            <li><a href="/yongo/administration/project/fields/<?php echo $projectId ?>">Fields</a></li>
            <li><a href="/yongo/administration/project/people/<?php echo $projectId ?>">People</a></li>
            <li><a href="/yongo/administration/project/permissions/<?php echo $projectId ?>">Permissions</a></li>
            <li><a href="/yongo/administration/project/issue-security/<?php echo $projectId ?>">Issue Security</a></li>
            <li><a href="/yongo/administration/project/notifications/<?php echo $projectId ?>">Notifications</a></li>
            <li><a href="/yongo/administration/project/versions/<?php echo $projectId ?>">Versions</a></li>
            <li><a href="/yongo/administration/project/components/<?php echo $projectId ?>">Components</a></li>
            <li><a href="/yongo/administration/project/helpdesk/<?php echo $projectId ?>">Helpdesk</a></li>
        </ul>

        <?php if ($session->get('user/super_user_flag')): ?>
            <?php if ($hasGlobalAdministrationPermission || $hasGlobalSystemAdministrationPermission): ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td><a href="/yongo/administration/project/workflows/select-project-workflow-scheme/<?php echo $projectId ?>" class="btn ubirimi-btn">Use a Different Scheme</a></td>
                    </tr>
                </table>
            <?php endif ?>
        <?php endif ?>

        <?php while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)): ?>
            <table width="100%">
                <tr>
                    <td colspan="2"><div class="headerPageText"><?php echo $workflow['name'] ?></div></td>
                </tr>
                <tr>
                    <td valign="top" width="180">
                        <?php $issueTypes = UbirimiContainer::get()['repository']->get(IssueTypeScheme::class)->getDataById($workflow['issue_type_scheme_id']); ?>
                        <div><b>These <?php echo $issueTypes->num_rows ?> issue types...</b></div>
                        <?php while ($issueType = $issueTypes->fetch_array(MYSQLI_ASSOC)): ?>
                            <div><?php echo $issueType['name'] ?></div>
                        <?php endwhile ?>
                    </td>
                    <td valign="top">
                        <div><b>... use this workflow definition</b></div>
                        <table class="table table-hover table-condensed">
                            <tr>
                                <td width="400">Step Name</td>
                                <td align="left">Transition</td>
                            </tr>
                            <?php $steps = UbirimiContainer::get()['repository']->get(Workflow::class)->getSteps($workflow['id']) ?>
                            <?php while ($step = $steps->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $step['step_name'] ?></td>
                                    <td align="left">
                                        <?php
                                            $transitions = UbirimiContainer::get()['repository']->get(Workflow::class)->getTransitionsForStepId($workflow['id'], $step['id']); ?>
                                        <?php if ($transitions): ?>
                                            <?php for ($i = 0; $i < $transitions->num_rows; $i++): ?>
                                                <?php $transition = $transitions->fetch_array(MYSQLI_ASSOC) ?>
                                                <div <?php if ($i < $transitions->num_rows - 1) echo 'class="tdElementList"' ?>><?php echo $transition['transition_name'] ?> >> <?php echo $transition['name'] ?></div>
                                            <?php endfor ?>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </td>
                </tr>
            </table>
        <?php endwhile ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>