<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb(sprintf('<a href="/helpdesk/all">Help Desks</a> > %s > Queues', $project['name'])); ?>

    <div class="pageContent">
        <?php require_once __DIR__ . '/../_topMenu.php' ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/helpdesk/queue/add/<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Queue</a>
                    <?php if ($queues): ?>
                        <a href="/helpdesk/queue/edit/<?php echo $queueSelected['id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                        <a id="btnDeleteQueue" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a>
                    <?php endif ?>
                </td>
            </tr>
        </table>

        <?php if ($queues): ?>
            <table width="100%">
                <tr>
                    <td width="200px" valign="top" rowspan="3">
                        <table width="100%" class="table table-hover table-condensed">
                            <?php while ($queue = $queues->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr>
                                    <td <?php if ($queue['id'] == $queueId) echo 'style="background-color: #f5f5f5;"' ?>>
                                        <a href="/helpdesk/queues/<?php echo $projectId ?>/<?php echo $queue['id'] ?>"><?php echo $queue['name'] ?> </a>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </td>
                    <td width="10px"></td>
                    <td valign="top">
                        <div class="headerPageText"><?php echo $queueSelected['name'] ?></div>
                    </td>
                    <td valign="top">
                        <div class="btn-group" style="float: right; margin-right: 0px;" id="btnIssueSearchColumns">
                            <a href="#" class="btn ubirimi-btn dropdown-toggle">Columns <span class="caret"></span></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left" colspan="2" valign="top">
                        <?php
                            $urlIssuePrefix = '/yongo/issue/';
                            require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_listResult.php';
                        ?>
                    </td>
                </tr>
            </table>
            <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/issue/search/_chooseDisplayColumns.php' ?>
        <?php else: ?>
            <div>There are no queues created.</div>
        <?php endif ?>
        <input type="hidden" value="<?php echo $queueId ?>" id="queue_id" />
        <input type="hidden" value="1" id="queue_context" />
        <input type="hidden" value="<?php echo $projectId ?>" id="project_id" />
        <div class="ubirimiModalDialog" id="modalDeleteQueue"></div>
    </div>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>