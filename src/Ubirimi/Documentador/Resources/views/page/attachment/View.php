<?php

    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > ' . $page['space_name'] . ' > ' . '<a class="linkNoUnderline" href="/documentador/pages/' . $spaceId . '">Pages</a> > <a class="linkNoUnderline" href="/documentador/page/view/' . $page['id'] . '">' . $page['name'] . '</a> > Attachments';
        Util::renderBreadCrumb($breadCrumb);
    ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/documentador/page/view/<?php echo $entityId ?>" class="btn ubirimi-btn">View Page</a></td>
            </tr>
        </table>

        <?php if ($attachments): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20px"></th>
                        <th>Name</th>
                        <th width="10%">Modified</th>
                        <th width="10%">Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($file = $attachments->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <img id="details_attachment_<?php echo $file['id'] ?>" src="/img/plus.png" width="16px"/>
                            </td>
                            <td><?php echo $file['name'] ?></td>
                            <td><?php echo $file['date_created'] ?></td>
                            <td>
                                <a class="btn ubirimi-btn" href="#" id="delete_doc_attachment_<?php echo $file['id'] ?>">Delete</a>
                            </td>
                        </tr>
                        <tr id="details_attachment_content_<?php echo $file['id'] ?>">
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are currently no attachments on this page.</div>
        <?php endif ?>

        <?php if ($loggedInUserId): ?>
            <br/>
            <form name="page_upload_file" method="post" enctype="multipart/form-data" action="/documentador/entity/upload-attachment/<?php echo $entityId ?>">
                <div style="border: dashed blue 1px; padding: 8px">
                    <div>To upload more attachments click the button bellow and then press Add Attachments</div>
                    <input style="padding: 4px" name="entity_upload_attachment[]" type="file" multiple="" value="Upload Attachments"/>
                    <input class="btn ubirimi-btn" type="submit" value="Add Attachments"/>
                </div>
                <input type="hidden" name="entity_id" value="<?php echo $entityId ?>"/>
            </form>
        <?php endif ?>

        <input type="hidden" value="<?php echo $entityId ?>" id="entity_id"/> <input type="hidden" value="<?php echo $spaceId ?>" id="space_id"/>

        <div class="ubirimiModalDialog" id="modalRemovePage"></div>
        <div class="ubirimiModalDialog" id="modalDeleteAttachment"></div>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>