<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > <?php echo $page['space_name'] ?> >
                        <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>">Pages</a> > <?php echo $page['name'] ?> > History</div>
                </td>
            </tr>
        </table>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnRestoreRevision" href="#" class="btn ubirimi-btn disabled">Restore this version</a></td>
                <td><a id="btnRemoveRevision" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Remove</a></td>
            </tr>
        </table>

        <?php if ($revisions): ?>
            <?php $revision = $revisions->fetch_array(MYSQLI_ASSOC) ?>
            <table width="100%" cellspacing="0px" cellpadding="0" border="0">
                <tr>
                    <td valign="top">
                        <table class="table table-hover table-condensed">
                            <tr>
                                <th></th>
                                <th>Version</th>
                                <th>Date</th>
                                <th>Changed By</th>
                            </tr>
                            <tr id="table_row_<?php echo $revision['id'] ?>">
                                <td width="22">
                                    <input disabled="disabled" type="checkbox" value="1" id="el_check_<?php echo $revision['id'] ?>" />
                                </td>
                                <td>
                                    <div>
                                        <span><b><a href="/documentador/page/view/<?php echo $entityId ?>">CURRENT (v. <?php echo $revisionCount-- ?>)</a></b></span>
                                    </div>
                                </td>
                                <td>
                                    <?php echo Util::getFormattedDate($page['date_created'], $clientSettings['timezone']) ?>
                                </td>
                                <td>
                                    <?php echo LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) ?>
                                </td>
                            </tr>

                            <?php while ($revision = $revisions->fetch_array(MYSQLI_ASSOC)): ?>
                                <tr id="table_row_<?php echo $revision['id'] ?>">
                                    <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $revision['id'] ?>" /></td>
                                    <td>
                                        <div>
                                            <span><a href="/documentador/page/view/<?php echo $entityId ?>/<?php echo $revision['id'] ?>"><b>v. <?php echo $revisionCount-- ?></a></b></span>
                                            <input type="hidden" value="<?php echo ($revisionCount + 1) ?>" id="revision_<?php echo $revision['id'] ?>" />
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo Util::getFormattedDate($page['date_created'], $clientSettings['timezone']) ?>
                                    </td>
                                    <td>
                                        <?php echo LinkHelper::getUserProfileLink($revision['user_id'], SystemProduct::SYS_PRODUCT_YONGO, $page['first_name'], $page['last_name']) ?>
                                    </td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no revisions for this page.</div>
        <?php endif ?>
        <div class="ubirimiModalDialog" id="modalRemoveRevision"></div>
        <div class="ubirimiModalDialog" id="modalRestoreRevision"></div>
        <input type="hidden" value="<?php echo $entityId ?>" id="entity_id" />
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>