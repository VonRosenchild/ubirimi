<?php
use Ubirimi\Util;

?>
<?php if (count($projectIdsNames)): ?>
    <div id="content_gadget_unresolved_others">
        <div style="padding: 4px">
            <span>Select Project</span>&nbsp;
            <select name="gadget_unresolved_others" id="gadget_unresolved_others_project" class="select2InputMedium">
                <option <?php if ($selectedProjectId == -1) echo 'selected="selected"' ?> value="-1">All Projects</div>
                <?php for ($i = 0; $i < count($projectIdsNames); $i++): ?>
                    <option <?php if ($selectedProjectId == $projectIdsNames[$i][0]) echo 'selected="selected"' ?> value="<?php echo $projectIdsNames[$i][0] ?>"><?php echo $projectIdsNames[$i][1] ?></option>
                <?php endfor ?>
            </select>
        </div>
        <?php if ($issuesUnresolvedOthers): ?>
            <?php
                $renderParameters = array('issues' => $issuesUnresolvedOthers, 'render_checkbox' => false, 'show_header' => true);
                $renderColumns = array('code', 'summary', 'priority', 'assignee');
                $issuesRendered = Util::renderIssueTables($renderParameters, $renderColumns, $clientSettings);
            ?>
            <?php if (!$issuesRendered): ?>
                <div>There are no unresolved issues assigned to others than you.</div>
            <?php endif ?>
        <?php else: ?>

            <div style="padding: 8px;">There are no unresolved issues assigned to others than you.</div>
        <?php endif ?>
    </div>
<?php else: ?>
    <div style="padding: 8px;">There are no projects to display information for.</div>
<?php endif ?>