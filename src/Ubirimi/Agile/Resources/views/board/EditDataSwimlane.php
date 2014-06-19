<?php
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

            <table cellspacing="4px">
                <tr>
                    <td>Name</td>
                    <td><?php echo $board['name'] ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><?php echo $board['description'] ?></td>
                </tr>
                <tr>
                    <td>Created by</td>
                    <td><?php echo LinkHelper::getUserProfileLink($board['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $board['first_name'], $board['last_name']) ?></td>
                </tr>
            </table>

            <ul class="nav nav-tabs" style="padding: 0px;">
                <li><a href="/agile/configure-board/<?php echo $boardId ?>" title="Summary">Filter</a></li>
                <li><a href="/agile/edit-board-columns/<?php echo $boardId ?>" title="Issues">Columns</a></li>
                <li class="active"><a href="/agile/board-swimlane/<?php echo $boardId ?>" title="Issues">Swimlanes</a></li>
            </ul>

            <div>A swimlane is a row on the board that can be used to group issues. Swimlane type can be changed below and will be saved automatically.</div>
            <br/>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td valign="top" width="150px">Base Swimlanes on</td>
                    <td>
                        <select name="swimlane" id="swimlane_strategy" class="inputTextCombo">
                            <option <?php if ($board['swimlane_strategy'] == 'story')
                                echo 'selected="selected"' ?> value="story">Stories
                            </option>
                            <option <?php if ($board['swimlane_strategy'] == 'assignee')
                                echo 'selected="selected"' ?> value="assignee">Assignees
                            </option>
                            <option <?php if ($board['swimlane_strategy'] == 'no_swimlane')
                                echo 'selected="selected"' ?> value="no_swimlane">No Swimlanes
                            </option>
                        </select>

                        <div id="swimlane_strategy_description">
                            <?php if ($board['swimlane_strategy'] == 'story'): ?>
                                Group sub-tasks under their parent issue. Issues without sub-tasks will be shown in their own group at the bottom.
                            <?php elseif ($board['swimlane_strategy'] == 'assignee'): ?>
                                Group issues by their assignee.
                            <?php elseif ($board['swimlane_strategy'] == 'no_swimlane'): ?>
                                No swimlanes will be displayed.
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <input id="board_id" value="<?php echo $boardId ?>" type="hidden"/>
    <?php require_once __DIR__ . '/../../../../Yongo/Resources/views/_footer.php' ?>
</body>