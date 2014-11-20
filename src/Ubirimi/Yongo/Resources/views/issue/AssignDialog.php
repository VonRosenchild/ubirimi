<?php
use Ubirimi\Yongo\Repository\Field\Field;

?>
<body>
    <table class="modal-table">
        <tr>
            <td valign="top">Assignee</td>
            <td>
                <select id="render_assign_issue_field_type_<?php echo Field::FIELD_ASSIGNEE_CODE ?>"
                        name="<?php echo Field::FIELD_ASSIGNEE_CODE ?>"
                        class="select2InputMedium">
                    <?php if ($allowUnassignedIssuesFlag): ?>
                        <option value="-1">No one</option>
                    <?php endif ?>
                    <?php while ($user = $assignableUsers->fetch_array(MYSQLI_ASSOC)): ?>
                        <option value="<?php echo $user['user_id'] ?>"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></option>
                    <?php endwhile ?>
                </select>
                <div class="requiredModalField" id="issue_new_ua_id_error"></div>
            </td>
        </tr>
        <tr>
            <td valign="top">Comment</td>
            <td>
                <textarea id="render_assign_issue_field_type_comment" class="inputTextAreaLarge"></textarea>
            </td>
        </tr>
    </table>
</body>