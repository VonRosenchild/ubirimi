<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Assignee</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_assignee" name="search_assignee[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['assignee']) || in_array(-1, $getSearchParameters['assignee'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <option value="current_user">Current User</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_user_assignee']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['assignee'])) {
                            $Ids = explode('#', $searchCriteria['all_client_user_assignee'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['assignee']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_user_assignee'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_user_assignee'][$i]['first_name'] . ' ' . $searchCriteria['all_client_user_assignee'][$i]['last_name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>