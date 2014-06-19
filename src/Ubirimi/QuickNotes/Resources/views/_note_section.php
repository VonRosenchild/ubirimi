<?php use Ubirimi\Util;

    if ($note): ?>
<table width="100%">
    <tr>
        <td align="left" style="background-color: #DDDDDD; width: 320px; height: 29px;">

            <div style="padding-left: 8px">
                <a href="#" class="btn ubirimi-btn" id="btnEditNote"><i class="icon-edit"></i> Update</a>
                <a href="#" class="btn ubirimi-btn" id="btnDeleteNote"><i class="icon-remove"></i> Delete</a>
                <div class="btn-group" >
                    <a href="#" class="btn ubirimi-btn dropdown-toggle"
                       data-toggle="dropdown">Move to <span class="caret"></span></a>

                    <ul class="dropdown-menu pull-left">
                        <?php foreach ($notebooks as $notebook): ?>
                            <li><a id="note_move_to_<?php echo $notebook['id'] ?>" href="#"><?php echo $notebook['name'] ?></a></li>
                        <?php endforeach ?>
                    </ul>
                </div>

            </div>
        </td>
        <td valign="top" rowspan="2" style="border-bottom: 1px solid #DDDDDD; background-color: #DDDDDD">
            <div id="tags">
                <?php while ($tags && $tag = $tags->fetch_array(MYSQLI_ASSOC)): ?>
                    <span class="tag" data="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></span>
                <?php endwhile ?>
                <input id="note_enter_new_tag" type="text" value="" placeholder="Add a tag" />
            </div>
        </td>

    </tr>
        <tr>
            <td style="border-bottom: 1px solid #DDDDDD; background-color: #DDDDDD">
                <div style="padding-left: 8px">Created <?php echo Util::getFormattedDate($note['date_created']) ?><?php if (isset($note['date_updated'])) echo ', Modified ' . Util::getFormattedDate($note['date_updated']) ?></div>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div contenteditable="true"  id="qn_note_title" style="padding-left: 8px; border-bottom: 1px solid #DDDDDD; font-size: 18px; margin-top: 8px;"><?php echo $note['summary'] ?></div>
            </td>
        </tr>
</table>

<?php require_once __DIR__ . '/Note/_note_tags_js.php' ?>
<div style="overflow: auto" id="parentNoteContent">
    <div id="note_content" style="padding-left: 8px"><?php echo $note['content'] ?></div>
</div>
<?php endif ?>