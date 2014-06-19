<?php
    use Ubirimi\LinkHelper;

?>
<div id="menu_child_pages" style="border: 1px solid #BBBBBB; z-index: 500; position: absolute; background-color: #ffffff; display: none; padding: 4px; padding-right: 10px;  box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5);">
    <table cellspacing="0" cellpadding="0" border="0">
        <?php if ($childPages): ?>
            <tr>
                <td width="20px"><img src="/documentador/img/tree_top.png" /></td>
                <td><?php echo $page['name'] ?></td>
            </tr>
            <?php $childPages->data_seek(0) ?>
            <?php while ($childPages && $childPage = $childPages->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td align="center">
                        <img src="/documentador/img/tree_child.png" style="margin-top: -24px"/>
                    </td>
                    <td><?php echo LinkHelper::getDocumentatorPageLink($childPage['id'], $childPage['name']) ?></td>
                </tr>
            <?php endwhile ?>
        <?php else: ?>
            <tr>
                <td>There are no child pages</td>
            </tr>
        <?php endif ?>
    </table>
    <div style="display: block; clear: both;"></div>
</div>