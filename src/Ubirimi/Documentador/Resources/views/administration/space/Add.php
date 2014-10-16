<?php
    require_once __DIR__ . '/../../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td>
                    <div class="headerPageText">
                        <a href="/documentador/spaces" class="linkNoUnderline">Spaces</a> > Create Space
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <form name="add_screen" action="/documentador/administration/spaces/add" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="150">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name)) echo $name; ?>" name="name" />
                        <?php if ($emptySpaceName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($doubleName): ?>
                            <div class="error">The name is not unique.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" width="150">Code <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($code)) echo $code; ?>" name="code" />
                        <?php if ($emptySpaceCode): ?>
                            <div class="error">The code can not be empty.</div>
                        <?php elseif ($doubleCode): ?>
                            <div class="error">The code is not unique.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea name="description" class="inputTextAreaLarge"><?php if (isset($description)) echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="add_space" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Space</button>
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