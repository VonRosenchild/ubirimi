<?php
    use Ubirimi\Yongo\Repository\Field\Field;
?>
<div style="max-height: 400px; overflow: auto; width: 800px">
    <div>Attach one or more files to the issue</div>
    <input id="field_type_attachment" type="file" name="<?php echo Field::FIELD_ATTACHMENT_CODE ?>[]" multiple=""/>

    <div id="progress"></div>
    <div id="fileList"></div>

    <div>Add Comment:</div>
    <textarea class="inputTextAreaLarge" id="attach_comment"></textarea>
</div>
