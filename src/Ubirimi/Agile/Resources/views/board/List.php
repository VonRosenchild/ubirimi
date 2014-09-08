<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Yongo\Repository\Project\Project;

    require_once __DIR__ . '/../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText">Manage Boards</div>
                </td>
            </tr>
        </table>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/agile/board/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Board</a></td>
                <td><a id="btnEditBoard" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteBoard" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <td><a id="btnConfigureBoard" href="#" class="btn ubirimi-btn disabled">Configure</a></td>
            </tr>
        </table>
        <?php if ($boards): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Owner</th>
                        <th>Saved Filter</th>
                        <th>Shares</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($board = $boards->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $board['id'] ?>">
                            <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $board['id'] ?>"/></td>
                            <td>
                                <div><a href="/agile/board/plan/<?php echo $board['id'] ?>"><?php echo $board['name'] ?></a></div>
                            </td>
                            <td>
                                <div><?php echo $board['description'] ?></div>
                            </td>
                            <td>
                                <?php echo LinkHelper::getUserProfileLink($board['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $board['first_name'], $board['last_name']) ?>
                            </td>
                            <td>
                                <a href="/yongo/issue/search?filter=<?php echo $board['filter_id'] ?>&<?php echo $board['filter_definition'] ?>"><?php echo $board['filter_name'] ?></a>
                            </td>
                            <td>
                                <?php
                                    $definition = $board['filter_definition'];
                                    $definitionData = explode("&", $definition);
                                    for ($i = 0; $i < count($definitionData); $i++) {
                                        $tempData = explode("=", $definitionData[$i]);
                                        if ($tempData[0] == 'project') {
                                            $projectIds = explode("|", $tempData[1]);
                                            $projects = Project::getByClientIdAndIds($clientId, $projectIds);
                                            echo '<ul>';
                                            while ($projects && $project = $projects->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/project/' . $project['id'] . '">' . $project['name'] . '</a></li>';
                                            }
                                            echo '</ul>';
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no board defined.</div>
        <?php endif ?>
    </div>

    <div class="ubirimiModalDialog" class="ubirimiModalDialog" id="modalDeleteBoard"></div>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>