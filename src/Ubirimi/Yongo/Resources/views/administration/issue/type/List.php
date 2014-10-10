<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueType;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../_menu.php'; ?>
    <?php Util::renderBreadCrumb('Issue Types') ?>

    <div class="pageContent">
        <?php if (Util::userHasYongoAdministrativePermission()): ?>
            <ul class="nav nav-tabs" style="padding: 0px;">
                <li class="active">
                    <a href="/yongo/administration/issue-types">Issue Types</a>
                </li>
                <li>
                    <a href="/yongo/administration/issue-type-schemes">Issue Type Schemes</a>
                </li>
                <li>
                    <a href="/yongo/administration/issue-sub-tasks">Sub-Tasks</a>
                </li>
            </ul>

            <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
                <tr>
                    <td><a id="btnNew" href="/yongo/administration/issue/add-type" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Issue Type</a></td>
                    <?php if ($types): ?>
                        <td><a id="btnEditIssueType" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                        <td><a id="btnDeleteIssueType" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                    <?php endif ?>
                </tr>
            </table>
            <?php if ($types): ?>
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Related schemes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($type = $types->fetch_array(MYSQLI_ASSOC)): ?>
                            <tr id="table_row_<?php echo $type['id'] ?>">
                                <td>
                                    <div>
                                        <input type="checkbox" value="1" id="el_check_<?php echo $type['id'] ?>" />
                                        <?php if ($type['icon_name']): ?>
                                            <img height="16px" src="/yongo/img/issue_type/<?php echo $type['icon_name'] ?>" />
                                        <?php endif ?>
                                        <?php echo $type['name'] ?>
                                    </div>
                                    <div class="smallDescription" style="padding-left: 25px"><?php echo $type['description'] ?></div>
                                </td>
                                <td width="500px">
                                    <ul>
                                        <?php
                                            $schemes = IssueType::getSchemesForIssueTypeId($type['id'], 'project');
                                            while ($schemes && $scheme = $schemes->fetch_array(MYSQLI_ASSOC)) {
                                                echo '<li><a href="/yongo/administration/issue-type-scheme/' . $scheme['id'] . '">' . $scheme['name'] . '</a></li>';
                                            }
                                        ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                <div class="ubirimiModalDialog" id="modalDeleteIssueType"></div>
            <?php else: ?>
                <div class="messageGreen">There are no issue types defined.</div>
            <?php endif ?>
        <?php else: ?>
            <?php Util::renderContactSystemAdministrator() ?>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../_footer.php' ?>
</body>
</html>