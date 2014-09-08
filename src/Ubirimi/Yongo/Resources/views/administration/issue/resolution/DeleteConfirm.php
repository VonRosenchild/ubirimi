<table>
    <tr>
        <td>
            <div>New resolution for matching issues: </div>
        </td>
        <td>
            <select class="ubirimiModalDialog" id="modal_delete_resolution" class="inputTextCombo">
                <?php while ($resolution = $resolutions->fetch_array(MYSQLI_ASSOC)): ?>
                <?php if ($resolution['id'] != $Id): ?>
                <option value="<?php echo $resolution['id'] ?>"><?php echo $resolution['name'] ?></option>
                <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>