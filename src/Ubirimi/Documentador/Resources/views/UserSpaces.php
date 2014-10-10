<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Repository\User\User;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Permission\GlobalPermission;

    require_once __DIR__ . '/../../Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../Resources/views/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Spaces'); ?>

    <div class="pageContent">
        <?php if (Util::checkUserIsLoggedIn()): ?>
            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <?php if (User::hasGlobalPermission($clientId, $loggedInUserId, GlobalPermission::GLOBAL_PERMISSION_DOCUMENTADOR_CREATE_SPACE)): ?>
                        <td><a id="btnNew" href="/documentador/administration/spaces/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Space</a></td>
                    <?php endif ?>
                </tr>
            </table>
        <?php endif ?>
        <?php if ($spaces): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Owner</th>
                        <th>Created</th>
                        <?php if (Util::checkUserIsLoggedIn()): ?>
                            <th>Favourite</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($space = $spaces->fetch_array(MYSQLI_ASSOC)): ?>

                        <tr>
                            <td>
                                <div><a href="/documentador/pages/<?php echo $space['space_id'] ?>"><?php echo $space['name'] ?></a></div>
                            </td>
                            <td>
                                <div><?php echo $space['code'] ?></div>
                            </td>
                            <td>
                                <div><?php echo $space['description'] ?></div>
                            </td>
                            <td>
                                <?php echo LinkHelper::getUserProfileLink($space['user_created_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $space['first_name'], $space['last_name']) ?>
                            </td>
                            <td>
                                <?php echo Util::getFormattedDate($space['date_created'], $clientSettings['timezone']) ?>
                            </td>
                            <?php if (Util::checkUserIsLoggedIn()): ?>
                                <td width="150px" align="center">
                                    <?php
                                        $isFavourite = Space::checkSpaceIsFavouriteForUserId($space['space_id'], $loggedInUserId);
                                        if ($isFavourite)
                                            echo '<img id="remove_space_from_favourites_' . $space['space_id'] . '" title="Remove Space from Favourites" src="/img/favourite_full.png" />';
                                        else
                                            echo '<img id="add_space_to_favourites_' . $space['space_id'] . '" title="Add Space to Favourites" src="/img/favourite_empty.png" />';
                                    ?>
                                </td>
                            <?php endif ?>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no spaces defined.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../Resources/views/_footer.php' ?>
</body>