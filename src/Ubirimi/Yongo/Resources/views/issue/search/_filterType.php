<tr>
    <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Issue Type</span></td>
</tr>
<tr>
    <td>
        <span>
            <select id="search_issue_type" name="search_issue_type[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['type']) || in_array(-1, $getSearchParameters['type'])) echo 'selected="selected"' ?>value="-1">Any</option>

                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_type']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['type'])) {
                            $Ids = explode('#', $searchCriteria['all_client_issue_type'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['type']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_issue_type'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_type'][$i]['name']; ?></option>
                <?php endfor ?>
            </select>
        </span>
    </td>
</tr>
