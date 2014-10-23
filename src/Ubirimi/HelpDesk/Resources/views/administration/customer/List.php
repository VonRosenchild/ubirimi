<?php
use Ubirimi\Util;

require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../../../../Resources/views/administration/_menu.php'; ?>
    <?php Util::renderBreadCrumb($breadCrumbTitle) ?>
    <div class="pageContent">

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <?php if (isset($organization)): ?>
                    <td><a href="/helpdesk/administration/customers/add?id=<?php echo $organization['id'] ?>" class="btn ubirimi-btn">Add Customer</a></td>
                <?php else: ?>
                    <td><a href="/helpdesk/administration/customers/add" class="btn ubirimi-btn">Add Customer</a></td>
                <?php endif ?>
                <td><a id="btnEditCustomer" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnDeleteCustomer" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($customers): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Email address</th>
                        <th>Organization</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($customer = $customers->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $customer['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $customer['id'] ?>" />
                            </td>
                            <td>
                                <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?>
                            </td>
                            <td><?php echo $customer['email'] ?></td>
                            <td><?php if (isset($organization)) echo $organization['name']; else echo $customer['organization_name'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
            <div class="ubirimiModalDialog" id="modalDeleteCustomer"></div>
        <?php else: ?>
            <div class="infoBox">There are no customers added to this organization.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../../../../Resources/views/administration/_footer.php' ?>
</body>