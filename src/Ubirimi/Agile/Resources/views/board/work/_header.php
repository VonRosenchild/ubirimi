<?php
        use Ubirimi\Agile\Repository\Board;

?>
<tr>
    <?php for ($i = 0; $i < count($columns); $i++): ?>
        <?php
            $statuses = Board::getColumnStatuses($columns[$i]['id'], 'array');
            $textStatuses = '';
            for ($k = 0; $k < count($statuses); $k++) {
                $textStatuses .= $statuses[$k]['id'] . '_' . $statuses[$k]['name'] . '#';
            }
        ?>
        <td style="height: 20px; background-color: #CCCCCC" class="pageContentSmall2" align="center" width="<?php echo(100 / count($columns)) ?>%">
            <div class="headerPageText"><?php echo $columns[$i]['name'] ?></div>
            <input type="hidden" id="status_for_column_<?php echo $columns[$i]['id'] ?>" value="<?php echo $textStatuses ?>"/>
        </td>
        <?php if ($i != (count($columns) - 1)): ?>
            <td>
                <div>&nbsp;&nbsp;</div>
            </td>
        <?php endif ?>
    <?php endfor ?>
</tr>