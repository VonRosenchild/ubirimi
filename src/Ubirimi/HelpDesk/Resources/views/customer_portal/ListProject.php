<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb("Projects") ?>
    <div class="pageContent">

        <?php if ($projects): ?>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($project = $projects->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td width="28" align="center">
                            <img class="projectIcon" id="project_icon" src="/img/project.png" height="20px" />
                        </td>
                        <td>
                            <a href="/helpdesk/customer-portal/project/<?php echo $project['id'] ?>"><?php echo $project['name']; ?></a>
                        </td>
                        <td><?php echo $project['code']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no projects created.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>