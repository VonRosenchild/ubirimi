<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = 'Tags';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="#" class="btn ubirimi-btn" id="btnCreateTag"><i class="icon-plus"></i> Create New Tag</a></td>
                <td><a id="btnEditTag" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteTag" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($tags): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($tag = $tags->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $tag['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $tag['id'] ?>"/>
                        </td>
                        <td>
                            <div><?php echo $tag['name'] ?></div>
                        </td>
                        <td>
                            <div><?php echo $tag['description'] ?></div>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no tags defined.</div>
        <?php endif ?>
    </div>

    <div class="ubirimiModalDialog" id="modalDeleteTag"></div>
    <div class="ubirimiModalDialog" id="modalCreateTag"></div>

    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>