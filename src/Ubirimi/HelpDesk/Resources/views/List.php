<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\HelpDesk\Repository\Queue\Queue;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../Yongo/Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb("Help Desks") ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/yongo/administration/project/add?helpdesk=true" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New HelpDesk</a></td>
            </tr>
        </table>

        <?php if ($projects): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td width="28" align="center">
                            <img class="projectIcon" id="project_icon" src="/img/project.png" height="20px" />
                        </td>
                        <td>
                            <?php
                                $queues = UbirimiContainer::get()['repository']->get(Queue::class)->getByProjectId($project['id']);
                                $queueSelectedId = -1;
                                if ($queues) {
                                    $queue = $queues->fetch_array(MYSQLI_ASSOC);
                                    $queueSelectedId = $queue['id'];
                                }
                            ?>
                            <a href="/helpdesk/queues/<?php echo $project['id'] ?>/<?php echo $queueSelectedId ?>"><?php echo $project['name']; ?></a>
                        </td>
                        <td><?php echo $project['code']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                        <td><?php echo LinkHelper::getUserProfileLink($project['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $project['first_name'], $project['last_name']); ?></td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no help desks created.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../../Yongo/Resources/views/_footer.php' ?>
</body>