<tr>
    <td colspan="<?php echo count($columns) + (count($columns) - 1) ?>">
        <div style="position: inherit; background-color: #f1f1f1; padding: 3px; border: 1px solid #acacac">
            <img border="0" src="/img/br_down.png" style="padding-bottom: 2px;" />
                <span>
                    <a href="#"><?php echo $user['first_name'] . ' ' . $user['last_name'] . '</a> ' . $issuesOfAssignee->num_rows . ' issues' ?>
                </span>
        </div>
    </td>
</tr>