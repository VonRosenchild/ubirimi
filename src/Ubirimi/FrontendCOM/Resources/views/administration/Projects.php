<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Issue\Issue;

require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <?php Util::renderBreadCrumb("Admin Home > Projects > Overview") ?>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($projects): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnDeleteProject" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th># Issues</th>
                <th>Description</th>
                <th>Code</th>
                <th>Client Domain</th>
                <th>Client Contact Email</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $project['id'] ?>">
                    <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $project['id'] ?>"/></td>
                    <td><?php echo $project['name']; ?></td>
                    <td>
                        <?php
                            $issuesResult = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('project' => $project['id']));
                            if ($issuesResult) {
                                echo $issuesResult->num_rows;
                            } else {
                                echo '0';
                            }
                        ?>
                    </td>
                    <td><?php echo $project['description']; ?></td>
                    <td><?php echo $project['code']; ?></td>
                    <td><?php echo $project['client_company_name']; ?></td>
                    <td><?php echo $project['contact_email']; ?></td>
                    <td><?php echo date('d F', strtotime($project['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteClient"></div>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no projects yet.</div>
    <?php endif ?>
</div>
