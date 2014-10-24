<?php

use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Sla\Sla;
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb(sprintf('<a href="/helpdesk/all">Help Desks</a> > %s > SLA', $project['name'])); ?>

    <div class="pageContent">
        <?php require_once __DIR__ . '/../../views/_topMenu.php'; ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/helpdesk/sla/add/<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create SLA</a>
                    <a href="/helpdesk/sla/edit/<?php echo $slaSelectedId?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                    <a href="#" id="btnDeleteSLA" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a>
                </td>
            </tr>
        </table>

        <?php if ($SLAs): ?>
            <table width="100%">
                <tr>
                    <td width="200px" valign="top">
                        <table width="100%" class="table table-hover table-condensed">
                            <?php while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td <?php if ($SLA['id'] == $slaSelectedId) echo 'style="background-color: #f5f5f5;"' ?>>
                                        <a href="/helpdesk/sla/<?php echo $projectId ?>/<?php echo $SLA['id'] ?>"><?php echo $SLA['name'] ?></a>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </td>
                    <td width="10px"></td>
                    <td valign="top">
                        <div class="headerPageText"><?php echo $slaSelected['name'] ?></div>
                        <div>Time will be measured between the Start and Stop conditions below.</div>
                        <br />
                        <table cellspacing="0" cellpadding="0" width="500px">
                            <tr>
                                <td>
                                    <span><b>Start</b></span>
                                    <br />
                                    <span>Begin counting time when</span>
                                </td>
                                <td width="100px"></td>
                                <td>
                                    <span><b>Stop</b></span>
                                    <br />
                                    <span>Finish counting time when</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                    <?php for ($i = 0; $i < count($startConditions); $i++): ?>
                                        <div class="slaCondition"><?php echo UbirimiContainer::get()['repository']->get(Sla::class)->transformConditionForView($startConditions[$i]) ?></div>
                                    <?php endfor ?>
                                </td>
                                <td></td>
                                <td align="left" valign="top">
                                    <?php for ($i = 0; $i < count($stopConditions); $i++): ?>
                                        <div class="slaCondition"><?php echo UbirimiContainer::get()['repository']->get(Sla::class)->transformConditionForView($stopConditions[$i]) ?></div>
                                    <?php endfor ?>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <div class="headerPageText">Goals</div>
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th width="400px">Issues</th>
                                    <th>Goal</th>
                                    <th>Calendar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($goals): ?>
                                    <?php while ($goal = $goals->fetch_array(MYSQLI_ASSOC)): ?>
                                        <tr>
                                            <td>
                                                <?php if ($goal['definition'] == 'all_remaining_issues'): ?>
                                                    <span>All remaining issues</span>
                                                    <?php $allRemainingIssuesDefinitionFound = true ?>
                                                <?php else: ?>
                                                    <?php echo $goal['definition'] ?>
                                                <?php endif ?>
                                            </td>
                                            <td><?php echo $goal['value'] ?> minutes</td>
                                            <td><?php echo $goal['calendar_name'] ?></td>
                                        </tr>
                                    <?php endwhile ?>
                                <?php endif ?>
                                <?php if (!$allRemainingIssuesDefinitionFound): ?>
                                    <tr>
                                        <td>All remaining issues</td>
                                        <td>No target</td>
                                        <td></td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div>There are no SLAs created.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteSLA"></div>
    <input type="hidden" value="<?php echo $slaSelectedId ?>" id="sla_id" />
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>