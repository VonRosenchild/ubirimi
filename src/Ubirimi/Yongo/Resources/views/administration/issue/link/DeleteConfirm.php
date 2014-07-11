<?php if ($issues): ?>
    <div>Are you sure you want to delete this issue link type?</div>

    <div>There are currently <?php echo $issues->num_rows ?> matching links.</div>
    <?php if ($linkTypes->num_rows > 1): ?>
        <input type="radio" checked="checked" name="action_remove_link" value="swap" id="swap_link_type" />
        <label for="swap_link_type">Swap current links to link type: </label>
        <select class="inputTextCombo" id="new_link_type">
        <?php while ($linkTypes && $linkType = $linkTypes->fetch_array(MYSQLI_ASSOC)): ?>
            <?php if ($linkType['id'] != $linkTypeId): ?>
                <option value="<?php echo $linkType['id'] ?>"><?php echo $linkType['name'] ?></option>
            <?php endif ?>
        <?php endwhile ?>
        </select>
        <br />
        <input type="radio" name="action_remove_link" value="remove" id="swap_link_type_remove" />
        <label for="swap_link_type_remove">Remove all links</label>
    <?php else: ?>
        <div>There are no other link types. All links will be removed.</div>
    <?php endif ?>
<?php else: ?>
    <div>Are you sure you want to delete this issue link type?</div>
<?php endif ?>