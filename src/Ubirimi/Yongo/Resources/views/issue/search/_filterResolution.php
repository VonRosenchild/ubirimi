<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Resolution</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_issue_resolution" name="search_issue_resolution[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['resolution']) || in_array(-1, $getSearchParameters['resolution'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_resolution']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['resolution'])) {
                            $Ids = explode('#', $searchCriteria['all_client_issue_resolution'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['resolution']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?>value="<?php echo $searchCriteria['all_client_issue_resolution'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_resolution'][$i]['name']; ?></option>
                <?php endfor ?>
                <option <?php if (isset($getSearchParameters['resolution']) && in_array(-2, $getSearchParameters['resolution'])) echo 'selected="selected"' ?>value="-2">Unresolved</option>
            </select>
        </td>
    </tr>
</table>
