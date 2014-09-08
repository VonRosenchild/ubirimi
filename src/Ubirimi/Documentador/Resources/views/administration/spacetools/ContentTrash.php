<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/documentador/administration/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>"><?php echo $space['name'] ?></a> > Space Tools > Content Tools</div>
                </td>
            </tr>
        </table>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>">Overview</a></li>
            <li><a href="/documentador/administration/space-tools/permissions/<?php echo $spaceId ?>">Permissions</a></li>
            <li class="active"><a href="/documentador/administration/space-tools/content/trash/<?php echo $spaceId ?>">Content Tools</a></li>
        </ul>

        <br />
        <div class="infoBox">The trash stores all deleted pages. You can restore or purge deleted pages from this screen.</div>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>">Trash</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($deletedPages): ?>
                    <td><a href="#" id="btnPurgeAll" class="btn ubirimi-btn">Purge All</a></td>
                <?php endif ?>
                <td><a href="#" id="btnPageRestore" class="btn ubirimi-btn disabled">Restore</a></td>
                <td><a href="#" id="btnPagePurge" class="btn ubirimi-btn disabled">Purge</a></td>
            </tr>
        </table>

        <?php if ($deletedPages): ?>
            <table width="100%" cellspacing="0px" cellpadding="0" border="0">
                <tr>
                    <td width="70%" valign="top">
                        <table class="table table-hover table-condensed">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Owner</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($page = $deletedPages->fetch_array(MYSQLI_ASSOC)): ?>
                                    <tr id="table_row_<?php echo $page['id'] ?>">
                                        <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $page['id'] ?>" /></td>
                                        <td>
                                            <div><a href="/documentador/page/view/<?php echo $page['id'] ?>"><?php echo $page['name'] ?></a></div>
                                        </td>
                                        <td>
                                            <?php echo LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) ?>
                                        </td>
                                        <td>
                                            <?php echo Util::getFormattedDate($page['date_created']) ?>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <div class="infoBox">There are currently no items in the trash.</div>
        <?php endif ?>
        <input type="hidden" value="<?php echo $spaceId ?>" id="space_id" />
        <div class="ubirimiModalDialog" id="modalPurgeAll"></div>
        <div class="ubirimiModalDialog" id="modalPageRestore"></div>
        <div class="ubirimiModalDialog" id="modalPagePurge"></div>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
