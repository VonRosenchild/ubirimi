<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <tr>
        <td>
            <div style="cursor: text;">
                <b>Recent Boards</b>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php if ($recentBoard): ?>
                <div><a href="/agile/board/plan/<?php echo $recentBoard['id'] ?>" class="linkSubMenu"><?php echo $recentBoard['name'] ?></a></div>
            <?php else: ?>
                <div>No board selected</div>
            <?php endif ?>
        </td>
    </tr>
    <tr>
        <td>
            <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
        </td>
    </tr>

    <tr>
        <td>
            <div style="cursor: text;">
                <b>All Boards</b>
            </div>
        </td>
    </tr>
    <?php while ($last5Board && $board = $last5Board->fetch_array(MYSQLI_ASSOC)): ?>
        <tr>
            <td>
                <div><a href="/agile/board/plan/<?php echo $board['id'] ?>" class="linkSubMenu"><?php echo $board['name'] ?></a></div>
            </td>
        </tr>
    <?php endwhile ?>
    <?php if (!$last5Board): ?>
        <tr>
            <td>
                <div>No Boards Defined</div>
            </td>
        </tr>
    <?php endif ?>
    <?php if ($clientAdministratorFlag): ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/agile/board/add">Create Board</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/agile/boards">Manage Boards</a>
                </div>
            </td>
        </tr>
    <?php endif ?>
</table>