<?php
    use Ubirimi\LinkHelper;

?>
<div style="display: block; clear: both;">
    <div class="headerPageText" style="border-bottom: 1px solid #DDDDDD;"><?php echo $childPages->num_rows ?> Child Page<?php if ($childPages->num_rows > 1) echo 's' ?></div>
    <div style="float: left; display: block; width: 100%; max-height: 300px; overflow: auto">
        <?php while ($childPage = $childPages->fetch_array(MYSQLI_ASSOC)): ?>
            <div><?php echo LinkHelper::getDocumentadorPageLink($childPage['id'], $childPage['name']) ?></div>
        <?php endwhile ?>
    </div>
</div>