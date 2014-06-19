<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <div class="pageContent">
        <?php
        Util::renderBreadCrumb('Issue Features > Linking')
        ?>

        <ul class="nav nav-tabs" style="padding: 0px;">
            <li><a href="/yongo/administration/issue-features/time-tracking">Time Tracking</a></li>
            <li class="active"><a href="/yongo/administration/issue-features/linking">Issue Linking</a></li>
        </ul>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if ($issueLinkingFlag): ?>
                    <td><a id="btnNew" href="/yongo/administration/link-type/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Link Type</a></td>
                    <td><a id="btnEditLinkType" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteLinkType" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
                <td><a href="/yongo/administration/toggle-issue-linking" class="btn ubirimi-btn"><?php if ($issueLinkingFlag) echo 'Deactivate'; else echo 'Activate' ?></a></td>
            </tr>
        </table>
        <?php if ($issueLinkingFlag): ?>
            <?php if ($linkTypes): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Outward Description</th>
                            <th>Inward Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($linkType = $linkTypes->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $linkType['id'] ?>">
                                <td width="22">
                                    <input type="checkbox" value="1" id="el_check_<?php echo $linkType['id'] ?>" />
                                </td>
                                <td><?php echo $linkType['name'] ?></td>
                                <td><?php echo $linkType['outward_description'] ?></td>
                                <td><?php echo $linkType['inward_description'] ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="infoBox">There are no link issue types defined.</div>
            <?php endif ?>
        <?php else: ?>
            <div class="infoBox">Issue linking is currently deactivated.</div>
        <?php endif ?>
    </div>
    <div id="modalDeleteLinkType"></div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>