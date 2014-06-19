<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Project Components</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_component" name="search_component[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['component']) || in_array(-1, $getSearchParameters['component'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_issue_component']); $i++): ?>
                    <?php
                    $found = false;
                    if (isset($getSearchParameters['component'])) {
                        $Ids = explode('#', $searchCriteria['all_client_issue_component'][$i]['id']);
                        $found = in_array($Ids[0], $getSearchParameters['component']);
                    }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_issue_component'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_issue_component'][$i]['name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>
