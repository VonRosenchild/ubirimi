<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

?>
<tr>
    <td id="sectAttachments" class="sectionDetail" colspan="2">
        <span class="sectionDetailTitle headerPageText">Attachments</span>
    </td>
</tr>
<tr>
    <td>
        <div id="contentAttachments">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table cellpadding="8px" cellspacing="8px">
                            <?php $counter = 0 ?>
                            <?php foreach ($attachments as $attachment): ?>
                                <?php if (0 == $counter % 10): ?>
                                    <tr>
                                <?php endif ?>
                                <?php if (Util::isImage(Util::getExtension($attachment['name']))): ?>
                                    <td style="border: solid 1px #d3d3d3;" class="thumbnail-item">
                                        <div align="center">
                                            <a class="fancybox" rel="group" href="/assets<?php echo UbirimiContainer::get()['asset.yongo_issue_attachments'] . $attachment['issue_id'] . '/' . $attachment['id'] . '/' . $attachment['name'] ?>">
                                                <img src="/assets<?php echo UbirimiContainer::get()['asset.yongo_issue_attachments'] . $attachment['issue_id'] . '/' . $attachment['id'] . '/thumbs/' . $attachment['name'] ?>" height="150" border="0" />
                                            </a>
                                        </div>
                                        <div>
                                            <div style="float: left;">
                                                <a target="_blank" href="/assets<?php echo UbirimiContainer::get()['asset.yongo_issue_attachments'] . $attachment['issue_id'] . '/' . $attachment['id'] . '/' . $attachment['name'] ?>">
                                                    <?php if (strlen($attachment['name']) > 25): ?>
                                                        <?php echo substr($attachment['name'], 0, 25) ?>...
                                                    <?php else: ?>
                                                        <?php echo $attachment['name'] ?>
                                                    <?php endif ?>
                                                </a>
                                            </div>
                                            <div style="float: right; margin-right: 0px;">
                                                <?php if ($hasDeleteAllAttachmentsPermission || ($hasDeleteOwnAttachmentsPermission && $loggedInUserId == $attachment['user_id'])): ?>
                                                    <img class="menu_img" style="vertical-align: bottom; margin-top: 5px" align="right" height="16px" src="/img/delete.png" id="list_att_<?php echo $attachment['id'] ?>" />
                                                <?php endif ?>
                                            </div>
                                        </div>
                                        <div style="clear: both;"></div>
                                        <div>
                                            <div style="float: left"><?php echo round($attachment['size'] / 1024, 2) ?> KB</div>
                                            <div style="float: right; margin-right: 0px;"><?php echo date('d/M/y', strtotime($attachment['date_created'])) ?></div>
                                        </div>
                                    </td>
                                <?php endif ?>

                                <?php $counter += 1 ?>

                                <?php if (0 == $counter % 10): ?>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%">
                            <?php foreach ($attachments as $attachment): ?>
                                <?php if (!Util::isImage(Util::getExtension($attachment['name']))): ?>
                                    <tr>
                                        <td width="75%">
                                            <a target="_blank" href="/assets<?php echo UbirimiContainer::get()['asset.yongo_issue_attachments'] . $attachment['issue_id'] . '/' . $attachment['id'] . '/' . $attachment['name'] ?>">
                                                <?php echo $attachment['name'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php echo round($attachment['size'] / 1024, 2) ?> KB
                                        </td>
                                        <td>
                                            <?php echo date('d/M/y H:i', strtotime($attachment['date_created'])) ?>
                                        </td>
                                        <?php if ($hasDeleteAllAttachmentsPermission || ($hasDeleteOwnAttachmentsPermission && $loggedInUserId == $attachment['user_id'])): ?>
                                            <td align="right">
                                                <img class="menu_img" style="vertical-align: bottom; margin-top: 5px" align="right" height="16px" src="/img/delete.png" id="list_att_<?php echo $attachment['id'] ?>" />
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </td>
</tr>