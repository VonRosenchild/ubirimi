<tr>
    <td colspan="<?php echo count($columns) + (count($columns) - 1) ?>">
        <div style="position: inherit; background-color: #f1f1f1; padding: 3px; border: 1px solid #acacac">
            <img border="0" src="/img/br_down.png" style="padding-bottom: 2px;" />
                <span>
                    <a href="#" id="agile_issue_<?php echo $issue['id'] ?>"><?php echo $issue['project_code'] . ' ' . $issue['nr'] ?></a>
                </span>
            <span><?php echo $childrenIssue->num_rows ?> sub-task<?php if ($childrenIssue->num_rows > 1) echo 's' ?></span>
            <span><?php echo $issue['summary'] ?></span>
        </div>
    </td>
</tr>