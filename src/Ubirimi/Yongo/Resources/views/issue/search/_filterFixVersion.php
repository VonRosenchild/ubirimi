<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Fix Version/s</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_fix_version" name="search_fix_version[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['fix_version']) || in_array(-1, $getSearchParameters['fix_version'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_version']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['fix_version'])) {
                            $Ids = explode('#', $searchCriteria['all_client_issue_version'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['fix_version']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_issue_version'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_version'][$i]['name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>
