<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../views/administration/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Administration > Organizations') ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a id="btnNew" href="/helpdesk/administration/organizations/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Organization</a></td>
                <?php if ($organizations): ?>
                    <td><a id="btnEditOrganization" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                    <td><a id="btnDeleteOrganization" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
                <?php endif ?>
            </tr>
        </table>
        <?php if ($organizations): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th># Customers</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($organization = $organizations->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $organization['id'] ?>">
                            <td>
                                <div>
                                    <input type="checkbox" value="1" id="el_check_<?php echo $organization['id'] ?>" />
                                </div>
                            </td>
                            <td>
                                <a href="/helpdesk/administration/customers?id=<?php echo $organization['id'] ?>"><?php echo $organization['name'] ?></a>
                            </td>
                            <td>
                                <?php echo $organization['description'] ?>
                            </td>
                            <td><a href="/helpdesk/administration/customers?id=<?php echo $organization['id'] ?>">View All</a></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteOrganization"></div>
        <?php else: ?>
            <div class="infoBox">There are no organizations defined.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../../views/administration/_footer.php' ?>
</body>