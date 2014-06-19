<?php
    require_once __DIR__ . '/../_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="pageContent">

        <form name="add_page" action="/documentador/spaces/add-page/<?php echo $spaceId ?><?php if ($parentEntityId) echo '/' . $parentEntityId ?>" method="post">
            <table width="100%" class="headerPageBackground">
                <tr>
                    <td>
                        <div class="headerPageText">
                            <a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > <a class="linkNoUnderline" href="/documentador/pages/<?php echo $spaceId ?>"><?php echo $space['name'] ?></a> > Create Page
                        </div>
                    </td>
                </tr>
            </table>

            <table width="100%" id="main_add_page">
                <tr>
                    <td>
                        <input class="inputTextLarge" id="doc_page_new_page" style="margin: 0px; padding: 4px; height: 30px; font: 22px Trebuchet MS, sans-serif;" type="text" value="<?php if (isset($name)) echo $name; else echo "New Page Title" ?>" name="name" tabindex="1" autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <td id="doc_parent_editor">
                        <textarea class="ckeditor" cols="80" name="content" id="entity_content" rows="10"></textarea>
                    </td>
                </tr>
                <tr>
                    <td><hr size="1" /></td>
                </tr>
                <tr>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_page" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Page</button>
                            <a class="btn ubirimi-btn" href="/documentador/spaces">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>
</html>