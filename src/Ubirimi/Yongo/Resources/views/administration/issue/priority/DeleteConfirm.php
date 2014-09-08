<table>
    <tr>
        <td>
            <div>New resolution for matching issues: </div>
        </td>
        <td>
            <select class="ubirimiModalDialog" id="modal_delete_priority" class="inputTextCombo">
                <?php while ($priority = $priorities->fetch_array(MYSQLI_ASSOC)): ?>
                <?php if ($priority['id'] != $Id): ?>
                <option value="<?php echo $priority['id'] ?>"><?php echo $priority['name'] ?></option>
                <?php endif ?>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
</table>