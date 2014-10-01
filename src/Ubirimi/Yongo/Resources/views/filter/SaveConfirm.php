<table class="modal-table">
    <tr>
        <td>Name</td>
        <td><input value="<?php if ($filter) echo $filter['name'] ?>" type="text" id="filter_name" class="inputText"></td>
    </tr>
    <tr>
        <td valign="top">Description</td>
        <td><textarea class="inputTextAreaLarge" id="filter_description"><?php if ($filter) echo $filter['description'] ?></textarea></td>
    </tr>
</table>