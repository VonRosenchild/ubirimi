<div id="content_chose_display_columns" style="display: none;">
    <div>Columns</div>
    <div style="height: 300px; overflow-y: auto">
        <table class="table table-hover table-condensed" width="100%">
            <tbody>
                <tr>
                    <td width="20px"><input <?php if (in_array('code', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_code"></td>
                    <td>Key</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('summary', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_summary"></td>
                    <td>Summary</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('description', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_description"></td>
                    <td>Description</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('priority', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_priority"></td>
                    <td>Priority</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('status', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_status"></td>
                    <td>Status</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('created', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_created"></td>
                    <td>Created</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('type', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_type"></td>
                    <td>Issue Type</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('updated', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_updated"></td>
                    <td>Updated</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('reporter', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_reporter"></td>
                    <td>Reporter</td>
                </tr>
                <tr>
                    <td><input <?php if (in_array('assignee', $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_assignee"></td>
                    <td>Assignee</td>
                </tr>
                <?php while ($SLAs && $SLA = $SLAs->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr>
                        <td><input <?php if (in_array('sla_' . $SLA['id'], $columns)) echo 'checked="checked"' ?> type="checkbox" id="issue_column_sla_<?php echo $SLA['id'] ?>"></td>
                        <td><?php echo $SLA['name'] ?></td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <div align="right">
        <button type="button" class="btn ubirimi-btn" id="btnUpdateIssueSearchColumns">Update</button>
        <button type="button" class="btn ubirimi-btn" id="btnIssueSearchColumnsCancel">Cancel</button>
    </div>
</div>