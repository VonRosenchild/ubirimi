<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <?php if (Util::userHasDocumentatorAdministrativePermission() || $spaces): ?>
            <?php Util::renderBreadCrumb('Spaces') ?>

            <?php if (Util::checkUserIsLoggedIn()): ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td><a id="btnNew" href="/documentador/administration/spaces/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Space</a></td>
                        <td><a id="btnEditSpace" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnSpaceTools" href="#" class="btn ubirimi-btn disabled">Space Tools</a></td>
                    </tr>
                </table>
            <?php endif ?>
            <?php if ($spaces): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Owner</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($space = $spaces->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $space['space_id'] ?>">
                                <?php if (Util::checkUserIsLoggedIn()): ?>
                                    <td width="22">
                                        <input type="checkbox" value="1" id="el_check_<?php echo $space['space_id'] ?>" />
                                    </td>
                                <?php endif ?>
                                <td>
                                    <div><a href="/documentador/pages/<?php echo $space['space_id'] ?>"><?php echo $space['name'] ?></a></div>
                                </td>
                                <td>
                                    <div><?php echo $space['code'] ?></div>
                                </td>
                                <td>
                                    <div class="smallDescription"><?php echo $space['description'] ?></div>
                                </td>
                                <td>
                                    <?php echo LinkHelper::getUserProfileLink($space['user_created_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $space['first_name'], $space['last_name']) ?>
                                </td>
                                <td>
                                    <?php echo Util::getFormattedDate($space['date_created'], $clientSettings['timezone']) ?>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <div class="ubirimiModalDialog" id="modalDeleteSpace"></div>
            <?php else: ?>
                <div class="infoBox">There are no spaces defined.</div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">Unauthorized access. Please contact the system administrator.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>