<?php
    require_once __DIR__ . '/_header.php';
?>
<body>

<?php require_once __DIR__ . '/_topMenu.php'; ?>
<div class="pageContent">
    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">Admin Home > SVN Respositories > Overview</div>
            </td>
        </tr>
    </table>

    <?php require_once __DIR__ . '/_menu.php' ?>

    <?php if ($svnRepositories): ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Description</th>
                <th>Client Name</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($svn = $svnRepositories->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $svn['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $svn['id'] ?>" />
                    </td>
                    <td><?php echo $svn['name']; ?></td>
                    <td><?php echo $svn['description']; ?></td>
                    <td><?php echo $svn['company_domain']; ?></td>
                    <td><?php echo date('d F', strtotime($svn['date_created'])) ?></td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
        <div class="ubirimiModalDialog" id="modalDeleteClient"></div>
    <?php else: ?>
        <div style="height: 2px"></div>
        <div class="messageGreen">There are no SVN Repositories created.</div>
    <?php endif ?>
</div>
</body>
