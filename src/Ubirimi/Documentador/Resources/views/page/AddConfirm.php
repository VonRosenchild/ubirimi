<table>
    <tr>
        <td>Space</td>
    </tr>
    <tr>
        <td>
            <select class="select2InputMedium" id="new_page_space">
                <?php while ($space = $spaces->fetch_array(MYSQLI_ASSOC)): ?>
                    <option <?php if ($space['space_id'] == $spaceId) echo 'selected="selected"' ?> value="<?php echo $space['space_id'] ?>"><?php echo $space['name'] ?></option>
                <?php endwhile ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><hr size="1" /> </td>
    </tr>
    <form id="new_entity_type" name="new_entity_type">
        <?php while ($type = $entityTypes->fetch_array(MYSQLI_ASSOC)): ?>
            <tr>
                <td>
                    <input <?php if ($position == 1) echo 'checked="checked"' ?> type="radio" name="entity_type" id="entity_type_<?php echo $type['id'] ?>" value="<?php echo $type['code'] ?>" />
                    <label for="entity_type_<?php echo $type['id'] ?>">
                        <b><?php echo $type['name'] ?></b>
                        <div><?php echo $type['description'] ?></div>
                    </label>
                </td>
            </tr>
            <?php $position++ ?>
        <?php endwhile ?>
    </form>
</table>