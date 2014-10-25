<?php
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;

?>
<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <?php if ($includeCheckbox): ?>
                <th></th>
            <?php endif ?>
            <th colspan="2">Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Project Lead</th>
            <th>Default Assignee</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($projects); $i++): ?>
            <?php if ($projects[$i]['category_id'] == $categoryId): ?>
                <tr id="table_row_<?php echo $projects[$i]['id'] ?>">
                    <?php if ($includeCheckbox): ?>
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $projects[$i]['id'] ?>" />
                        </td>
                    <?php endif ?>
                    <td width="20px">
                        <img class="projectIcon" id="project_icon" src="/img/project.png" height="20px" />
                    </td>
                    <td valign="middle">
                        <a href="/yongo/<?php if ($administrationView) echo 'administration/' ?>project/<?php echo $projects[$i]['id'] ?>"><?php echo $projects[$i]['name']; ?></a>
                    </td>
                    <td><?php echo $projects[$i]['code']; ?></td>
                    <td><?php echo $projects[$i]['description']; ?></td>
                    <td><?php echo LinkHelper::getUserProfileLink($projects[$i]['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $projects[$i]['first_name'], $projects[$i]['last_name']); ?></td>
                    <td>Project Lead</td>
                </tr>
            <?php endif ?>
        <?php endfor ?>
    </tbody>
</table>