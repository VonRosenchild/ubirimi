<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>

    <?php Util::renderBreadCrumb("Project Categories") ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/yongo/administration/project/category/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Category</a></td>
                <td><a id="btnEditProjectCategory" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteProjectCategory" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($projectCategories): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($category = $projectCategories->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $category['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $category['id'] ?>" /></td>
                            <td><?php echo $category['name']; ?></td>
                            <td><?php echo $category['description']; ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no project categories defined.</div>
        <?php endif ?>
    </div>
    <div class="ubirimiModalDialog" id="modalDeleteProjectCategory"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>