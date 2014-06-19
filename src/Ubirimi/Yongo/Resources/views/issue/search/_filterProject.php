<tr>
    <td id="sectDates" class="sectionDetail"><span class="sectionDetailTitle">In Project</span></td>
</tr>
<tr>
    <td>
        <span>
            <select id="search_project_list" name="search_project_list[]" multiple="multiple" class="search_combobox">
                <option value="-1" <?php if (!isset($getSearchParameters['project']) || in_array(-1, $getSearchParameters['project'])) echo 'selected="selected"' ?>>
                    All projects
                </option>
                <?php foreach ($projectIdsAndNames as $value): ?>
                    <?php
                        $found = false;
                        if (isset($getSearchParameters['project'])) $found = in_array($value['id'], $getSearchParameters['project'])
                    ?>
                    <option <?php if ($found) echo 'selected="selected"' ?> value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                <?php endforeach ?>
            </select>
        </span>
    </td>
</tr>
