<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityComment;
    use Ubirimi\Repository\Documentador\EntityType;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent" style="overflow: hidden;">
        <?php if ($page): ?>
            <?php
                $breadCrumb = '<a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > ' . $page['space_name'] . ' > ' .
                              '<a class="linkNoUnderline" href="/documentador/pages/' . $spaceId . '">Pages</a> > ';

                if ($parentPage)
                    $breadCrumb .= LinkHelper::getDocumentatorPageLink($parentPage['id'], $parentPage['name'], 'linkNoUnderline') . ' > ';

                $breadCrumb .= $page['name'];
                Util::renderBreadCrumb($breadCrumb);
            ?>

            <?php if (Util::checkUserIsLoggedIn()): ?>
                <?php require_once __DIR__ . '/_buttons.php' ?>
            <?php endif ?>
            <?php
                $lastEditedText = ' last edited by ';
                if ($lastRevision) {
                    $date = date("F t, Y", strtotime($lastRevision['date_created']));
                    $lastEditedText .= LinkHelper::getUserProfileLink($lastRevision['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $lastRevision['first_name'], $lastRevision['last_name']) . ' on ' . $date;
                } else {
                    $date = date("F t, Y", strtotime($page['date_created']));
                    $lastEditedText .= LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) . ' on ' . $date;
                }

                $linkAttachments = '';
                if ($attachments)
                    $linkAttachments = '<a href="/documentador/page/attachments/' . $entityId . '"><img border="0" src="/img/attachment.png" height="10px" /></a> <a href="/documentador/page/attachments/' . $entityId . '">' . $attachments->num_rows . '</a>';
            ?>
            <div class="smallDescription"><?php echo $linkAttachments ?> Added by <?php echo LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) ?>, <?php echo $lastEditedText ?></div>

            <div>
                <?php
                    if ($revisionId) {
                        echo '<div class="infoBox">';
                        echo '<div>You are viewing an old version of this page. View the <a href="/documentador/page/view/' . $entityId . '">current version</a>.</div>';
                        echo '<div>Restore this Version | <a href="/documentador/page/history/' . $entityId . '">View Page History</a></div>';
                        echo '</div>';
                        echo $revision['content'];
                    } else {
                        echo $page['content'];
                    }
                ?>
            </div>

            <?php if ($page['documentator_entity_type_id'] == EntityType::ENTITY_FILE_LIST): ?>
                <?php if ($pageFiles): ?>
                    <br />
                    <?php require_once __DIR__ . '/_listFiles.php' ?>
                <?php endif ?>
                <br />
                <form name="page_upload_file" method="post" enctype="multipart/form-data" action="/documentador/entity/upload/<?php echo $entityId ?>">
                    <div style="border: dashed blue 1px; padding: 8px">
                        <div>To upload more files click the button bellow and then press Add Files</div>
                        <input style="padding: 4px" name="entity_upload_file[]" type="file" multiple="" value="Upload Files"/>
                        <input class="btn ubirimi-btn" type="submit" value="Add Files" />
                    </div>
                </form>
            <?php endif ?>
            <br />
            <div>
                <?php if ($childPages): ?>
                    <?php require_once __DIR__ . '/_listChildPagesSmall.php' ?>
                <?php endif ?>

                <div id="pageCommentsSection" style="display: block; clear: both;">
                    <?php if ($childPages && $comments): ?>
                        <br />
                    <?php endif ?>

                    <?php if ($comments): ?>
                        <div class="headerPageText" style="border-bottom: 1px solid #DDDDDD;"><?php echo count($comments) ?> Comment<?php if (count($comments) > 1) echo 's' ?></div>
                        <div style="float: left; display: block; width: 100%">
                            <?php
                                $htmlLayout = '';
                                EntityComment::getCommentsLayoutHTML($comments, $htmlLayout, null, 0);
                                echo $htmlLayout;
                            ?>
                        </div>
                    <?php endif ?>
                </div>

                <div style="display: block; clear: both;">
                    <br />
                    <?php if (Util::checkUserIsLoggedIn()): ?>
                        <textarea class="inputTextAreaLarge" id="doc_view_page_add_comment_content" style="width: 100%">Add a comment</textarea>
                        <div style="height: 2px"></div>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                    <div>
                                        <input type="button" name="add_comment" id="doc_view_page_add_comment" value="Add Comment" class="btn ubirimi-btn"/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    <?php endif ?>
                </div>
            </div>
            <?php if (Util::checkUserIsLoggedIn()): ?>
                <?php require_once __DIR__ . '/_childPagesSubmenu.php' ?>

                <input type="hidden" value="<?php echo $entityId ?>" id="entity_id" />
                <input type="hidden" value="<?php echo $spaceId ?>" id="space_id" />
                <div id="modalDeleteComment"></div>
                <div id="modalRemovePage"></div>
                <div id="modalDeleteFile"></div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">This page does not exist.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>