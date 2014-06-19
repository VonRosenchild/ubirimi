<?php
    $optionsHTML = '';
    if ($issueEditableProperty && $issue['parent_id'] == null && $subTaskIssueTypes && $hasCreatePermission) {
        $optionsHTML .= '<li><a id="issue_add_subtask" href="#">Create Sub-Task</a></li>';
    }

    if ($hasCreatePermission) {
        $optionsHTML .= '<li><a id="issue_duplicate" href="#">Duplicate</a></li>';
    }

    if ($timeTrackingFlag && $hasWorkOnIssuePermission) {
        $optionsHTML .= '<li><a id="mwnu_add_issue_log_work" href="#">Log Work</a></li>';
    }

    if ($issueEditableProperty && $hasCreateAttachmentPermission) {
        $optionsHTML .= '<li><a id="sub_menu_issue_add_attachment" href="#">Attach File</a></li>';
    }

    if ($issueLinkingFlag && $hasLinkIssuePermission) {
        $optionsHTML .= '<li><a id="issue_add_link" href="#">Link</a></li>';
    }

    if ($hasMoveIssuePermission && !$parentIssue) {
        $optionsHTML .= '<li><a href="/yongo/issue/move/project/' . $issue['id'] . '">Move</a></li>';
    }
?>
<div class="btn-group">
    <a href="#" class="btn ubirimi-btn dropdown-toggle <?php if (!$optionsHTML) echo 'disabled' ?>" data-toggle="dropdown">More Actions <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <?php echo $optionsHTML ?>
    </ul>
</div>