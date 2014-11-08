<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Documentador\Repository\Entity\Entity;
use Ubirimi\Documentador\Repository\Space\Space;
use Ubirimi\LinkHelper;
use Ubirimi\SystemProduct;
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>

    <?php if (Util::checkUserIsLoggedIn() || $spaceHasAnonymousAccess): ?>
        <?php
            $title = '<a class="linkNoUnderline" href="/documentador/spaces">Spaces</a> > ' . $space['name'] . ' > Pages';
            Util::renderBreadCrumb($title);
        ?>
    <?php endif ?>


    <div class="doc-left-side">
        <div>
            <?php
                $html = '';
                echo UbirimiContainer::get()['repository']->get(Entity::class)->renderTreeNavigation($treeStructure, 0, 0, true);
            ?>
        </div>
    </div>
    <div class="pageContent" id="content" style="margin-left: 285px;">
        <?php if (Util::checkUserIsLoggedIn() || $spaceHasAnonymousAccess): ?>
            <?php if (Util::checkUserIsLoggedIn()): ?>
                <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                    <tr>
                        <td><a id="btnNewDocumentadorPage" href="/documentador/spaces/add-page/<?php echo $spaceId ?><?php if ($space['home_entity_id']) echo '/' . $space['home_entity_id'] ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Page</a></td>
                        <?php if ($homePage): ?>
                            <td><a href="/documentador/page/view/<?php echo $space['home_entity_id'] ?>" class="btn ubirimi-btn">Home Page</a></td>
                        <?php else: ?>
                            <td><a class="btn ubirimi-btn disabled">Home Page</a></td>
                        <?php endif ?>
                        <td><a id="btnEditPage" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeletePage" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete Page</a></td>
                        <?php if (UbirimiContainer::get()['repository']->get(Space::class)->userHasAdminSpacePermission($space['space_id'], $loggedInUserId)): ?>
                            <td><a href="/documentador/administration/space/edit/<?php echo $space['space_id'] ?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit Space</a></td>
                            <td><a id="btnDeleteSpaceFromPages" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete Space</a></td>
                            <td><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>" class="btn ubirimi-btn">Space Tools</a></td>
                        <?php endif ?>
                    </tr>
                </table>
            <?php endif ?>

            <?php if ($pages): ?>
                <table width="100%" cellspacing="0px" cellpadding="0" border="0">
                    <tr>
                        <td>
                            <table width="100%" cellspacing="0px" cellpadding="0" border="0">
                                <tr>
                                    <td width="70%" valign="top">
                                        <table class="table table-hover table-condensed" <?php if ($loggedInUserId) echo ''; else echo 'id="content_list"' ?> cellpadding="0" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <?php if ($loggedInUserId): ?>
                                                    <th></th>
                                                <?php endif ?>
                                                <th>Name</th>
                                                <th>Owner</th>
                                                <th>Created</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($page = $pages->fetch_array(MYSQLI_ASSOC)): ?>
                                                <tr id="table_row_<?php echo $page['id'] ?>">
                                                    <?php if ($loggedInUserId): ?>
                                                        <td width="22"><input type="checkbox" value="1" id="el_check_<?php echo $page['id'] ?>" /></td>
                                                    <?php endif ?>
                                                    <td>
                                                        <div><a href="/documentador/page/view/<?php echo $page['id'] ?>"><?php echo $page['name'] ?></a></div>
                                                    </td>
                                                    <td>
                                                        <?php echo LinkHelper::getUserProfileLink($page['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $page['first_name'], $page['last_name']) ?>
                                                    </td>
                                                    <td>
                                                        <?php echo Util::getFormattedDate($page['date_created'], $clientSettings['timezone']) ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <div class="infoBox">There are no pages defined in this space.</div>
            <?php endif ?>
            <div class="ubirimiModalDialog" id="modalDeleteSpace"></div>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <div style="margin-left: 285px">
        <?php require_once __DIR__ . '/_footer.php' ?>
    </div>


    <input type="hidden" id="page_tree_browser_visible" value="1" />
    <input type="hidden" value="<?php echo $spaceId ?>" id="space_id" />

    <div class="ubirimiModalDialog" id="modalRemovePage"></div>
</body>