<?php
    require_once __DIR__ . '/_header.php';


use Ubirimi\Repository\General\UbirimiClient;
use Ubirimi\Yongo\Repository\Project\YongoProject;
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">
                    Admin Home > Issues > Overview
                </div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($issues): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnDeleteClient" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th width="200">Client</th>
                <th width="200">Project</th>
                <th width="750">Summary</th>
                <th>Created at</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($issue = $issues->fetch_array(MYSQLI_ASSOC)): ?>
                <?php
                    $project = $this->getRepository(YongoProject::class)->getById($issue['issue_project_id']);
                    $client = $this->getRepository(UbirimiClient::class)->getById($project['client_id']);
                ?>
                <tr id="table_row_<?php echo $issue['id'] ?>">
                    <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $issue['id'] ?>"/></td>
                    <td><?php echo $client['company_name'] ?></td>
                    <td><?php echo substr($issue['project_name'], 0, 100) ?></td>
                    <td><?php echo substr($issue['summary'], 0, 70) ?></td>

                    <td><?php echo date('d F', strtotime($issue['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteClient"></div>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no issues yet.</div>
    <?php endif ?>
</div>
</body>
