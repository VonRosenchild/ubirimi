<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb('<a class="linkNoUnderline" href="/calendar/calendars">Calendars</a> > Create Calendar') ?>
    <div class="pageContent">
        <form name="add_calendar" action="/calendar/add" method="post">
            <table width="100%">
                <tr>
                    <td valign="top" width="200">Name <span class="mandatory">*</span></td>
                    <td>
                        <input class="inputText" type="text" value="<?php if (isset($name))
                            echo $name ?>" name="name"/>
                        <?php if ($emptyName): ?>
                            <div class="error">The name can not be empty.</div>
                        <?php elseif ($duplicateName): ?>
                            <div class="error">A calendar with the same name already exists.</div>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Description</td>
                    <td>
                        <textarea class="inputTextAreaLarge" name="description"><?php if (isset($description)) echo $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top">Color</td>
                    <td>
                        <input class="inputText color {hash:true}" style="width: 100px" name="color" value="<?php if (isset($color)) echo $color; else echo '#A1FF9E'; ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr size="1"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">
                        <div align="left">
                            <button type="submit" name="confirm_new_calendar" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Calendar</button>
                            <a class="btn ubirimi-btn" href="/calendar/calendars">Cancel</a>
                        </div>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>