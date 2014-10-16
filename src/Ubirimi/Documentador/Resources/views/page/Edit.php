<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>"><?php echo $space['name'] ?></a> > <?php echo $page['name'] ?> > Update Page
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">

        <form name="add_page" action="/documentador/page/edit/<?php echo $entityId ?>" method="post">
            <div class="warningBox" id="snapshotWarning" id=""><?php if ($lastUserSnapshot) echo 'A version of this page you were editing at ' . $lastUserSnapshot['date_created'] . ' was saved as a draft. Do you want to <a href="/documentador/entity/resume-edit/' . $lastUserSnapshot['id'] . '">resume editing</a> or <a id="snapshot_discard" href="#">discard it</a>?'; ?></div>
            <div class="warningBox" id="multipleEntityEdits"><?php if ($textWarningMultipleEdits) echo $textWarningMultipleEdits; ?></div>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <input style="margin: 0; width: 100%; padding: 4px; height: 30px; font: 22px Trebuchet MS, sans-serif;"
                               id="doc_page_edit_page"
                               class="inputText"
                               type="text"
                               value="<?php echo $name; ?>"
                               name="name" />
                    </td>
                </tr>
                <tr>
                    <td id="doc_parent_editor">
                        <div style="height: 4px"></div>
                        <textarea class="ckeditor" name="content" id="entity_content"><?php echo $page['content'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td><hr size="1" /></td>
                </tr>
                <tr>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_page" value="1dsa" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Page</button>
                            <a class="btn ubirimi-btn" href="/documentador/page/edit/cancel/<?php echo $spaceId ?>/<?php echo $entityId ?>">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
        <input type="hidden" value="1" id="entity_edit_context" />
        <input type="hidden" value="<?php echo $entityId ?>" id="entity_id" />
        <input type="hidden" value="<?php if ($lastUserSnapshot) echo $lastUserSnapshot['id']; else echo "-1"; ?>" id="entity_last_snapshot" />
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>