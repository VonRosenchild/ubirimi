<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
<?php require_once __DIR__ . '/../_menu.php'; ?>
<?php
    $breadCrumb = 'My Notebooks';
    Util::renderBreadCrumb($breadCrumb);
?>
<div class="pageContent">

    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td><a href="#" class="btn ubirimi-btn" id="btnCreateNotebook"><i class="icon-plus"></i> Create New Notebook</a></td>
            <td><a id="btnEditNotebook" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
            <td><a id="btnDeleteNotebook" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
        </tr>
    </table>

    <?php if ($notebooks): ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Primary Notebook</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($notebook = $notebooks->fetch_array(MYSQLI_ASSOC)): ?>
                <tr id="table_row_<?php echo $notebook['id'] ?>">
                    <td width="22">
                        <?php if (0 == $notebook['default_flag']): ?>
                            <input type="checkbox" value="1" id="el_check_<?php echo $notebook['id'] ?>"/>
                        <?php endif ?>
                    </td>
                    <td>
                        <div><?php echo $notebook['name'] ?></div>
                    </td>
                    <td>
                        <div><?php echo $notebook['description'] ?></div>
                    </td>
                    <td>
                        <?php if ($notebook['default_flag']): ?>
                            <div>Yes (can not be deleted)</div>
                        <?php else: ?>
                            <div>No</div>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="infoBox">There are no notebooks defined.</div>
    <?php endif ?>
</div>

<div class="ubirimiModalDialog" id="modalDeleteNotebook"></div>

<?php require_once __DIR__ . '/../_footer.php' ?>
</body>