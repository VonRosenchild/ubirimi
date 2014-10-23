<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb(sprintf('<a href="/helpdesk/all">Help Desks</a> > %s > Reports > %s', $project['name'], $slaSelected['name'])); ?>

    <div class="pageContent">
        <?php require_once __DIR__ . '/../../views/_topMenu.php'; ?>
        <div style="padding-top: 4px; padding-bottom: 4px"></div>
        <?php if ($SLAs): ?>
            <table width="100%">
                <tr>
                    <td width="200px" valign="top">
                        <table width="100%" class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th>Current Reports</th>
                                </tr>
                            </thead>
                            <?php while ($SLA = $SLAs->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td <?php if ($SLA['id'] == $slaSelectedId) echo 'style="background-color: #f5f5f5;"' ?>>
                                        <a href="/helpdesk/report/<?php echo $projectId ?>/<?php echo $SLA['id'] ?>"><?php echo $SLA['name'] ?></a>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </td>
                    <td width="10px"></td>
                    <td valign="top">
                        <div class="headerPageText"><?php echo $slaSelected['name'] ?></div>
                        <div>
                            <span>Start Date</span>
                            <input type="text" class="inputText" style="width: 80px" value="<?php echo $dateFrom ?>" id="sla_report_start_date" />
                            <span>End Date</span>
                            <input type="text" class="inputText" style="width: 80px" value="<?php echo $dateTo ?>" id="sla_report_end_date"/>
                        </div>
                        <br />
                        <div id="chartContainer" style="height: 700px;"></div>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div>There are no SLAs created.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteSLA"></div>
    <input type="hidden" value="<?php echo $slaSelectedId ?>" id="sla_id_for_report" />
    <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>