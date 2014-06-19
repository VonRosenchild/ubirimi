<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">Reporter</span></td>
    </tr>
    <tr>
        <td>
            <select id="search_reporter" name="search_reporter[]" multiple="multiple" class="search_combobox">
                <option <?php if (!isset($getSearchParameters['reporter']) || in_array(-1, $getSearchParameters['reporter'])) echo 'selected="selected"' ?>value="-1">Any</option>
                <?php for ($i = 0; $i < count($searchCriteria['all_client_user_reporter']); $i++): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['reporter'])) {
                            $Ids = explode('#', $searchCriteria['all_client_user_reporter'][$i]['id']);
                            $found = in_array($Ids[0], $getSearchParameters['reporter']);
                        }
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $searchCriteria['all_client_user_reporter'][$i]['id'] ?>"><?php echo $searchCriteria['all_client_user_reporter'][$i]['first_name'] . ' ' . $searchCriteria['all_client_user_reporter'][$i]['last_name']; ?></option>
                <?php endfor ?>
            </select>
        </td>
    </tr>
</table>
