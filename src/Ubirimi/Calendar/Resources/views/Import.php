<?php

    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php Util::renderBreadCrumb("Import Calendar"); ?>
    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/calendar/calendars" class="btn ubirimi-btn">Go Back</a></td>
            </tr>
        </table>

        <div class="infoBox">Here you can import Outlook calendars</div>
        <form name="import_calendar" method="post" enctype="multipart/form-data" action="/calendar/import">
            <div style="border: dashed blue 1px; padding: 8px">
                <input name="calendar_file" type="file" value="Upload Files"/> <input class="btn ubirimi-btn" type="submit" name="import_calendar" value="Import"/>
            </div>
        </form>

        <div>Import calendar from URL</div>
        <form name="import_calendar_url" method="post" action="/calendar/import">
            <input type="text" class="inputTextLarge" name="calendar_url" />
            <input type="submit" value="Import" name="import_calendar_url" class="btn ubirimi-btn" />
        </form>
    </div>
    <?php require_once __DIR__ . '/_footer.php' ?>
</body>