<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

$selectedProjectId = $projectIdsNames[0][0];
    $selectedProjectId = -1;
    if (isset($projectId)) {
        $selectedProjectId = $projectId;
    }

    $projectIds = array();

    foreach ($projectIdsNames as $key => $value) {
        $projectIds[] = $value[0];
    }

    $projectIdsString = implode('|', $projectIds);
?>

<div id="content_gadget_two_dimensional_filter">
    <span>Select Project</span>&nbsp;
    <select name="gadget_two_dimensional_filter" id="gadget_two_dimensional_filter" class="select2InputMedium">
        <option <?php if ($selectedProjectId == -1) echo 'selected="selected"' ?> value="-1">All Projects</div>
        <?php for ($i = 0; $i < count($projectIdsNames); $i++): ?>
            <?php $selectedText = (isset($projectId) && $projectId == $projectIdsNames[$i][0]) ? 'selected="selected"' : '' ?>
            <option <?php echo $selectedText ?>
                value="<?php echo $projectIdsNames[$i][0] ?>"><?php echo $projectIdsNames[$i][1] ?></option>
        <?php endfor ?>
    </select>

    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>Assignee</th>
            <?php for ($i = 0; $i < count($issueStatuses); $i++): ?>
                <th><?php echo $issueStatuses[$i]['name'] ?></th>
            <?php endfor ?>
        </tr>
        </thead>
        <tbody>
        <?php while ($userAsAssignee = $usersAsAssignee->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td> <?php echo LinkHelper::getUserProfileLink($userAsAssignee['id'], SystemProduct::SYS_PRODUCT_YONGO, $userAsAssignee['first_name'], $userAsAssignee['last_name']) ?></td>
                <?php for ($i = 0; $i < count($issueStatuses); $i++): ?>
                    <?php
                        $found = false;
                        $count = 0;
                        if ($twoDimensionalData) {
                            foreach ($twoDimensionalData as $data) {
                                if ($data['id'] == $userAsAssignee['id'] && $data['status_id'] == $issueStatuses[$i]['id']) {
                                    $found = true;
                                    $count = $data['count'];
                                    break;
                                }
                            }
                        }
                    ?>
                    <td>
                        <a href="/yongo/issue/search?assignee=<?php echo $userAsAssignee['id'] ?>&project=<?php echo $selectedProjectId == -1 ? $projectIdsString : $selectedProjectId ?>&status=<?php echo $issueStatuses[$i]['id'] ?>"><?php echo $count ?></a>
                    </td>
                <?php endfor ?>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
</div>