<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">

        <?php Util::renderBreadCrumb('Filters') ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNewFilter" href="/yongo/issue/search" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Filter</a></td>
                <td><a id="btnDeleteFilter" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($filters): ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Favorite</th>
                    <th>Created on</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($filter = $filters->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $filter['id'] ?>">
                        <?php $boards = AgileBoard::getByFilterId($filter['id']) ?>
                        <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $filter['id'] ?>" /></td>
                        <td><a href="/yongo/issue/search?filter=<?php echo $filter['id'] ?>&<?php echo $filter['definition'] ?>"><?php echo $filter['name'] ?></a></td>
                        <td><?php echo $filter['name'] ?></td>
                        <td><?php echo $filter['description'] ?></td>
                        <td><?php echo Util::getFormattedDate($filter['date_created']) ?></td>
                        <?php if ($boards): ?>
                            <input type="hidden" value="0" id="delete_filter_possible_<?php echo $filter['id'] ?>" />
                        <?php else: ?>
                            <input type="hidden" value="1" id="delete_filter_possible_<?php echo $filter['id'] ?>" />
                        <?php endif ?>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="messageGreen">There are no filters defined.</div>
        <?php endif ?>
    </div>
    <div id="deleteFilterModal" class="ubirimiModalDialog"></div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>