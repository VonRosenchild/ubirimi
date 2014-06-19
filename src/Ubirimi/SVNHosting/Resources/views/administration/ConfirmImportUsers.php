<?php if (empty($importableUsers)): ?>
    0
<?php else: ?>
    <table width="100%">
        <tr>
            <td align="center">
                <select name="import_users" id="import_users" size="20" style="width: 200px" multiple="multiple">
                    <?php foreach ($importableUsers as $user): ?>
                        <option value="<?php echo $user['id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                    <?php endforeach ?>
                </select>
            </td>
        </tr>
    </table>
<?php endif ?>