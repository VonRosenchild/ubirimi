<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/../../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">
        <form name="edit_workflow" action="/documentador/administration/space/edit/<?php echo $spaceId ?><?php if (isset($backLink)) echo '?back=' . $backLink ?>" method="post">
            <?php
                $breadCrumb = '<a class="linkNoUnderline" href="/documentador/spaces">Spaces</a> > ' . $space['name'] . ' > Update';
                Util::renderBreadCrumb($breadCrumb);
            ?>
            <table width="100%">
                <tr>
                    <td width="200" valign="top">Name <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $space['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td width="200" valign="top">Code</td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $space['code'] ?>" name="code" />
                        <?php if ($emptyCode): ?>
                            <div class="error">The code can not be empty.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>Homepage</td>
                    <td>
                        <select name="homepage" class="select2Input">
                            <?php while ($pages && $page = $pages->fetch_array(MYSQLI_ASSOC)): ?>
                                <option <?php if ($space['home_entity_id'] == $page['id']) echo 'selected="selected"' ?> value="<?php echo $page['id'] ?>"><?php echo $page['name'] ?></option>
                            <?php endwhile ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $space['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="let">
                        <div align="left">
                            <button type="submit" name="edit_space" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Space</button>
                            <?php if (isset($backLink) && $backLink == 'space_tools'): ?>
                                <a class="btn ubirimi-btn" href="/documentador/administration/space-tools/overview/<?php echo $spaceId ?>">Cancel</a>
                            <?php else: ?>
                                <a class="btn ubirimi-btn" href="/documentador/administration/spaces">Cancel</a>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>