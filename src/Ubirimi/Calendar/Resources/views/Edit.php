<?php

    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/calendar/calendars">Calendars</a> > Edit') ?>

    <div class="pageContent">
        <form name="edit_status" action="/calendar/edit/<?php echo $calendarId ?>" method="post">
            <table width="100%">
                <tr>
                    <td valign="top">Name <span class="error">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php echo $calendar['name'] ?>" name="name" />
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($calendarExists): ?>
                            <div class="error">A calendar with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php echo $calendar['description'] ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Color</td>
                    <td>
                        <input class="inputText color {hash:true}" style="width: 100px" name="color" value="<?php if (isset($calendar['color'])) echo $calendar['color'] ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><hr size="1" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="edit_calendar" class="btn ubirimi-btn"><i class="icon-edit"></i> Update Calendar</button>
                            <a class="btn ubirimi-btn" href="/calendar/calendars">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>
</html>