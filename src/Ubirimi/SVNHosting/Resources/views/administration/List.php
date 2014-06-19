<?php

    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;

    require_once __DIR__ . '/../_header.php';
?>
<body>
<?php require_once __DIR__ . '/_menu.php'; ?>
<div class="pageContent">

    <table width="100%" class="headerPageBackground">
        <tr>
            <td>
                <div class="headerPageText">Administration > Repositories</div>
            </td>
        </tr>
    </table>

    <ul class="nav nav-tabs" style="padding: 0px;">
        <li class="active">
            <a href="/svn-hosting/administration/all-repositories">Repositories</a>
        </li>
        <li>
            <a href="/svn-hosting/administration/administrators">Administrators</a>
        </li>
    </ul>

    <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
        <tr>
            <td>
                <a id="btnNew" href="/svn-hosting/administration/repository/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New SVN Repository</a>
            </td>
            <td>
                <a id="btnEditSvnRepo" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a>
            </td>
            <td>
                <a id="btnDeleteSvnRepo" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a>
            </td>
        </tr>
    </table>

    <?php if (!empty($svnRepos)): ?>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Created by</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($svnRepos as $repo): ?>
                <tr id="table_row_<?php echo $repo['id'] ?>">
                    <td width="22">
                        <input type="checkbox" value="1" id="el_check_<?php echo $repo['id'] ?>" />
                    </td>
                    <td align="left">
                        <a href="/svn-hosting/administration/repository/<?php echo $repo['id'] ?>"><?php echo $repo['name']; ?></a>
                    </td>
                    <td><?php echo $repo['code']; ?></td>
                    <td><?php echo $repo['description']; ?></td>
                    <td><?php echo LinkHelper::getUserProfileLink($repo['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $repo['first_name'], $repo['last_name']); ?></td>
                    <td><?php echo $repo['date_created'] ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <div id="deleteSvnRepo"></div>
    <?php else: ?>
        <div class="messageGreen">There are no svn repositories created.</div>
    <?php endif ?>
</div>
<?php require_once __DIR__ . '/_footer.php' ?>
</body>