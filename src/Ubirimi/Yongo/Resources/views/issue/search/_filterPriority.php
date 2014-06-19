<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Priority</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_issue_priority" name="search_issue_priority[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['priority']) || in_array(-1, $getSearchParameters['priority'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_priority']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['priority'])) {
                            $Ids = explode('#', $searchCriteria['all_client_issue_priority'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['priority']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_issue_priority'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_priority'][$i]['name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>
