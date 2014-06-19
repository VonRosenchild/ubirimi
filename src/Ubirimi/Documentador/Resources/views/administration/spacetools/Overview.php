<?php
    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <table width="100%" class="headerPageBackground">
            <tr>
                <td>
                    <div class="headerPageText"><a href="/documentador/administration/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>"><?php echo $space['name'] ?></a> > Space Tools > Overview</div>
                </td>
            </tr>
        </table>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li class="active"><a href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>">Overview</a></li>
            <li><a href="/documentador/administration/space-tools/permissions/<?php echo $spaceId ?>">Permissions</a></li>
            <li><a href="/documentador/administration/space-tools/content/trash/<?php echo $spaceId ?>">Content Tools</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/documentador/administration/space/edit/<?php echo $spaceId ?>?back=space_tools" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteSpaceFromSpaceTools" href="#" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Name:</td>
                <td><?php echo $space['name'] ?></td>
            </tr>
            <tr>
                <td>Code:</td>
                <td><?php echo $space['code'] ?></td>
            </tr>
            <tr>
                <td>Homepage:</td>
                <td><?php echo LinkHelper::getDocumentatorPageLink($space['home_entity_id'], $space['home_page_name']) ?></td>
            </tr>
            <tr>
                <td>Created By:</td>
                <td><?php echo LinkHelper::getUserProfileLink($space['user_id'], SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $space['first_name'], $space['last_name']) ?></td>
            </tr>
        </table>
    </div>
    <div id="modalDeleteSpace"></div>
    <input id="space_id" type="hidden" value="<?php echo $spaceId ?>" />
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>