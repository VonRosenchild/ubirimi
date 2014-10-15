<?php if ($type == 'file_list'): ?>
    <table>
        <tr>
            <td>Name <span class="mandatory">*</span></td>
            <td valign="top">
                <input type="text" class="inputText" value="" id="entity_name" />
            </td>
        </tr>
        <tr>
            <td valign="top">Description</td>
            <td>
                <textarea class="inputTextAreaLarge" id="entity_description" />
            </td>
        </tr>
    </table>
<?php endif ?>