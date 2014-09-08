<?php if ($issuesCount): ?>
    <table>
        <tr>
            <td colspan="2">

                <div>Confirm that you want to delete this issue type and specify what is to be done with the issues currently attached to it.</div>
                <div>Note: This issue type will be removed from all field configurations, issue type screens and workflow schemes.</div>
            </td>
        </tr>
        <tr>
            <td width="250px">
                <div>New type for matching issues: </div>
            </td>
            <td>
                <select class="ubirimiModalDialog" id="modal_delete_type" class="inputTextCombo">
                    <?php while ($type = $types->fetch_array(MYSQLI_ASSOC)): ?>
                        <?php if ($type['id'] != $Id): ?>
                            <option value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                        <?php endif ?>
                    <?php endwhile ?>
                </select>
            </td>
        </tr>
    </table>
<?php else: ?>
    <table>
        <tr>
            <td>
                <div>Confirm that you want to delete this issue type</div>
                <div>Note: This issue type will be removed from all field configuration, issue type screen and workflow schemes.</div>
            </td>
        </tr>
    </table>
<?php endif ?>