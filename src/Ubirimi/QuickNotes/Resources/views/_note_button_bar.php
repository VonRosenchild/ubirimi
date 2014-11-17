<?php
use Ubirimi\Util;
?>

<table width="100%" id="qn_note_button_options" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left" style="background-color: #DDDDDD; width: 324px;<?php if (!$note) echo "border-bottom: 2px solid #c6c6c6;" ?> height: 34px;">
            <?php if ($notebooks && $note): ?>
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
            <?php endif ?>
        </td>
        <td align="right" valign="top" style="<?php if (!$note) echo "border-bottom: 2px solid #c6c6c6;" ?> background-color: #DDDDDD">
            <div id="tags" style="width: 100%;">
                <?php if (isset($tags)): ?>
                    <?php while ($tags && $tag = $tags->fetch_array(MYSQLI_ASSOC)): ?>
                        <span class="tag" data="<?php echo $tag['id'] ?>"><?php echo $tag['name'] ?></span>
                    <?php endwhile ?>
                <?php endif ?>
                <input id="note_enter_new_tag" type="text" value="" placeholder="Add a tag" />
            </div>
        </td>
    </tr>
    <?php if ($note): ?>
        <tr>
            <td colspan="2" style="border-bottom: 2px solid #c6c6c6; background-color: #DDDDDD">
                <div style="padding-left: 8px">Created <?php echo Util::getFormattedDate($note['date_created']) ?><?php if (isset($note['date_updated'])) echo ', Modified ' . Util::getFormattedDate($note['date_updated']) ?></div>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div contenteditable="true"  id="qn_note_title" style="padding-left: 8px; border-bottom: 1px solid #DDDDDD; font-size: 18px; margin-top: 8px;"><?php echo $note['summary'] ?></div>
            </td>
        </tr>
    <?php endif ?>
</table>