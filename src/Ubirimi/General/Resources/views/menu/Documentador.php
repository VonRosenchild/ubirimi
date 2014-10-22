<?php
use Ubirimi\Util;

?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableMenu">
    <?php if (Util::checkUserIsLoggedIn()): ?>
        <tr>
            <td>
                <div>
                    <a class="linkSubMenu" href="/documentador/dashboard/spaces">Dashboard</a>
                </div>
            </td>
        </tr>
    <?php endif ?>
    <tr>
        <td>
            <div>
                <a class="linkSubMenu" href="/documentador/spaces">Spaces</a>
            </div>
        </td>
    </tr>

    <?php if ($spaces): ?>
        <tr>
            <td>
                <span style="border-bottom: 1px solid #BBBBBB; margin-bottom: 4px; padding-bottom: 4px; display: block;"></span>
            </td>
        </tr>
        <?php while ($space = $spaces->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td>
                    <div>
                        <?php if ($space['home_entity_id']): ?>
                            <a class="linkSubMenu" href="/documentador/page/view/<?php echo $space['home_entity_id'] ?>"><?php echo $space['name'] ?></a>
                        <?php else: ?>
                            <a class="linkSubMenu" href="/documentador/pages/<?php echo $space['space_id'] ?>"><?php echo $space['name'] ?></a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endwhile ?>
    <?php endif ?>
</table>