<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="pageContent">

        <?php Util::renderBreadCrumb('Users > Profile Manager > Categories') ?>
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/general-settings/users/profile-manager/category/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Category</a></td>
                <td><a id="btnEditProfileCategory" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnUpdateUserProfileFields" href="#" class="btn ubirimi-btn disabled">Update Fields</a></td>
                <td><a id="btnDeleteProfileCategory" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($profileCategories): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Fields</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($profileCategory = $profileCategories->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $profileCategory['id'] ?>">
                            <td width="22">
                                <input type="checkbox" value="1" id="el_check_<?php echo $profileCategory['id'] ?>" />
                            </td>

                            <td>
                                <?php echo $profileCategory['name'] ?>
                            </td>
                            <td><?php echo $profileCategory['description'] ?></td>
                            <td></td>
                            <td>
                                <a href="/general-settings/users/edit/<?php echo $profileCategory['id'] ?>">Edit</a>
                                &middot;
                                <a href="#" class="deleteFromGeneralList" data="<?php echo $profileCategory['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no profile categories defined.</div>
        <?php endif ?>
        <div class="ubirimiModalDialog" id="modalDeleteUserProfileCategory"></div>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>