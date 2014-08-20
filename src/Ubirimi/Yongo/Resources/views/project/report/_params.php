<form name="chart_report" action="/yongo/project/reports/<?php echo $projectId ?>/chart-report" method="post">
    <table width="100%" cellpadding="4px">
        <tbody>
        <tr>
            <td width="200px" valign="top" align="right">
                <div>Project or saved filter</div>
            </td>
        </tr>
        <tr>
            <td align="right" valign="top">
                <div>Statistic Type</div>
            </td>
            <td valign="top" style="width: 200px">
                <select name="statistic_type" class="select2InputSmall">
                    <option value="assignee">Assignee</option>
                </select>
                <div>Select which type of statistic to display for this filter</div>
            </td>
            <td valign="top">
                <select name="chart_type" id="chart_type" class="select2InputSmall">
                    <option <?php if ('column' == $chartType) echo 'selected="selected"' ?> value="column">Column</option>
                    <option <?php if ('pie' == $chartType) echo 'selected="selected"' ?> value="pie">Pie</option>
                </select>
                <div>Display chart type</div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <hr size="1" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" name="show_report" class="btn ubirimi-btn">Show Report</button>
                <a class="btn ubirimi-btn" href="/documentador/administration/groups">Cancel</a>
            </td>
        </tr>
        </tbody>
    </table>
</form>