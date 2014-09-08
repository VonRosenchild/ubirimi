<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;

    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>

    <div class="pageContent">
        <form name="add_board" action="/agile/board/add" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a class="linkNoUnderline" href="/agile/boards">Agile Boards</a> > <?php echo $board['name'] ?> > Configure
                        </div>
                    </td>
                </tr>
            </table>
            <table class="table table-hover table-condensed" cellspacing="4px">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $board['name'] ?></td>
                        <td><?php echo $board['description'] ?></td>
                        <td><?php echo LinkHelper::getUserProfileLink($board['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $board['first_name'], $board['last_name']) ?></td>
                    </tr>
                </tbody>
            </table>
            <br />
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li id="p_tab_issues_overview"><a href="/agile/configure-board/<?php echo $boardId ?>" title="Filter">Filter</a></li>
                <li id="p_tab_issues" class="active"><a href="/agile/edit-board-columns/<?php echo $boardId ?>" title="Columns">Columns</a></li>
                <li><a href="/agile/board-swimlane/<?php echo $boardId ?>" title="Issues">Swimlanes</a></li>
            </ul>

            <div>Columns can be added, removed, reordered and renamed. Columns are based upon global statuses and can be moved between columns.</div>

            <div align="right">
                <input type="button" value="Add Column" class="btn ubirimi-btn" id="addAgileColumn"/>
            </div>
            <br/>

            <div id="containerColumns">
                <table width="100%" cellspacing="0" border="0" cellpadding="0" id="agile_columns">
                    <tr id="sortable" id="agile_columns_id">
                        <td width="<?php echo $columnWidth . '%'; ?>" valign="top" class="notSortable">
                            <table width="100%" cellspacing="0" cellpadding="4px" style="border: 1px solid #DDDDDD;" class="notSortable">
                                <tr>
                                    <td align="left" style="height: 54px; border-bottom: 1px solid #DDDDDD;">
                                        <div style="font-size: 16px; text-align: center;">Unmapped Statuses</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="column_0" class="droppable" valign="top" style="height: 200px;">
                                        <?php for ($j = 0; $j < count($unmappedStatuses); $j++): ?>
                                        <div id="status_<?php echo $unmappedStatuses[$j]['id'] ?>" class="statusAgile draggable">&nbsp;<?php echo $unmappedStatuses[$j]['name'] ?></div>
                                        <?php endfor ?>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <?php for ($i = 0; $i < count($columns); $i++): ?>
                            <td width="<?php echo $columnWidth . '%'; ?>" valign="top" class="sortable draggableColumn" id="agile_move_column_<?php echo $columns[$i]['id'] ?>">
                                <table width="100%" cellspacing="0" cellpadding="4px" style="border: 1px solid #DDDDDD; border-collapse: collapse;">
                                    <tr>
                                        <td align="left" valign="top" style="border: 1px solid #DDDDDD;">
                                            <div style=" width: 10%; float: left;" class="agile-drag-handler">
                                                <img src="/img/drag.png" />&nbsp;
                                            </div>
                                            <div style="float: right; width: 16px;">
                                                <img title="Delete Column" id="deleteAgileColumn_<?php echo $columns[$i]['id'] ?>" src="/img/delete.png" />
                                            </div>
                                            <div style="clear: both;"></div>
                                            <div style="font-size: 16px; text-align: center;"><?php echo $columns[$i]['name'] ?></div>
                                        </td>
                                    </tr>

                                    <?php $statuses = AgileBoard::getColumnStatuses($columns[$i]['id'], 'array') ?>
                                    <tr>
                                        <td id="column_<?php echo $columns[$i]['id'] ?>" class="droppable" valign="top" style="height: 200px;">
                                        <?php if ($statuses): ?>
                                            <?php for ($j = 0; $j < count($statuses); $j++): ?>
                                            <div id="status_<?php echo $statuses[$j]['id'] ?>" class="statusAgile draggable">&nbsp;<?php echo $statuses[$j]['name'] ?></div>
                                            <?php endfor ?>
                                        <?php endif ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        <?php endfor ?>
                    </tr>
                </table>
            </div>
        </form>
        <input id="board_id" value="<?php echo $boardId ?>" type="hidden"/>

        <div class="ubirimiModalDialog" class="ubirimiModalDialog" id="modalAddAgileColumn"></div>
        <div class="ubirimiModalDialog" class="ubirimiModalDialog" id="modalDeleteAgileColumn"></div>
        <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
    </div>
</body>