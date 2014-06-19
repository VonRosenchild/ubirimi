<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Status</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_issue_status" name="search_issue_status[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['status']) || in_array(-1, $getSearchParameters['status'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_status']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['status'])) {
                            $Ids = explode('#', $searchCriteria['all_client_issue_status'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['status']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?>value="<?php echo $searchCriteria['all_client_issue_status'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_status'][$i]['name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>