<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Versions</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <?php
            $menuProjectCategory = 'versions';
            require_once __DIR__ . '/_summaryMenu.php';
            require_once __DIR__ . '/_projectButtons.php';
        ?>

        <?php if ($releases): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($release = $releases->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><a href="/yongo/project/version/<?php echo $release['id'] ?>"><?php echo $release['name']; ?></a></td>
                    <td><?php echo $release['description']; ?></td>
                </tr>
                <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteProjectRelease"></div>
        <?php else: ?>
            <div class="messageGreen">There are no versions for this project yet.</div>
        <?php endif ?>
    </div>
    <input type="hidden" id="project_id" value="<?php echo $projectId ?>" name="project_id" />
    <div class="ubirimiModalDialog" id="modalProjectFilters"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>