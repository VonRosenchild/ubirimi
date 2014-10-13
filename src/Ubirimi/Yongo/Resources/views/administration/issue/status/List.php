<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <?php Util::renderBreadCrumb('Issue Statuses') ?>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active"><a href="/yongo/administration/issue/statuses">Statuses</a></li>
                <li><a href="/yongo/administration/issue/resolutions">Resolutions</a></li>
                <li><a href="/yongo/administration/issue/priorities">Priorities</a></li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/status/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Status</a></td>
                    <?php if ($statuses): ?>
                        <td><a id="btnEditIssueStatus" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeleteIssueStatus" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>
            <div class="infoBox">
                <div>All statuses have one of two modes: Active - associated with a workflow/s. Inactive - not in use. To delete a status, it must not be associated with a workflow.</div>
            </div>

            <?php if ($statuses): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Workflows</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($status = $statuses->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $status['id'] ?>">
                                <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $status['id'] ?>"/></td>
                                <td><?php echo $status['name']; ?></td>
                                <td><?php echo $status['description']; ?></td>
                                <td>
                                    <?php $workflows = $this->getRepository('yongo.workflow.workflow')->getByIssueStatusId($status['id']); ?>
                                    <?php if ($workflows): ?>
                                        <div>Active</div>
                                    <?php else: ?>
                                        <div>Inactive</div>
                                    <?php endif ?>
                                    <input type="hidden" id="delete_possible_<?php echo $status['id'] ?>" value="<?php if ($workflows) echo "0"; else echo "1" ?>"/>
                                </td>
                                <td width="500px">
                                    <?php
                                        if ($workflows) {
                                            echo '<ul>';
                                            while ($workflow = $workflows->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/workflow/view-as-text/' . $workflow['id'] . '">' . $workflow['name'] . '</a></li>';
                                            }
                                            echo '</ul>';
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <div id="deleteIssueStatus"></div>
            <?php else: ?>
                <div class="messageGreen">There are no issue statuses defined.</div>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>