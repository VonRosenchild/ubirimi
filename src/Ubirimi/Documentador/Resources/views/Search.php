<?php

    use Ubirimi\LinkHelper;
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('Search'); ?>
    <div class="pageContent">
        <form name="search_documentator" method="post" action="/documentador/search">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <input name="keyword" type="text" value="<?php echo $searchQuery ?>" class="inputTextLarge" />
                        <button type="submit" name="search" class="btn ubirimi-btn">Search</button>
                    </td>
                </tr>
                <tr>
                    <td><hr size="1" /></td>
                </tr>
            </table>
        </form>
        <?php if ($pages): ?>
            <table>
            <?php while ($page = $pages->fetch_array(MYSQLI_ASSOC)): ?>
                <tr>
                    <td><?php echo LinkHelper::getDocumentatorPageLink($page['id'], $page['name']) ?></td>
                </tr>
            <?php endwhile ?>
            </table>
        <?php else: ?>
            <div class="infoBox">No results found for <b><?php echo $searchQuery ?></b></div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>