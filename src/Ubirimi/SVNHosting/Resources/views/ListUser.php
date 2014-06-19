<?php

    use Ubirimi\LinkHelper;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb('My SVN Repositories') ?>

        <?php if (!empty($svnRepos)): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Created by</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($svnRepos as $repo): ?>
                    <tr>
                        <td align="left">
                            <a href="/svn-hosting/repository/<?php echo $repo['id'] ?>"><?php echo $repo['name']; ?></a>
                        </td>
                        <td><?php echo $repo['code']; ?></td>
                        <td><?php echo $repo['description']; ?></td>
                        <td><?php echo LinkHelper::getUserProfileLink($repo['user_created_id'], SystemProduct::SYS_PRODUCT_YONGO, $repo['first_name'], $repo['last_name']); ?></td>
                        <td><?php echo Util::getFormattedDate($repo['date_created']) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="messageGreen">There are no svn repositories created.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>