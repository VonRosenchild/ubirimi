<?php
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php
        Util::renderBreadCrumb("My Calendars");
    ?>
    <div class="pageContent">
        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td><a href="/calendar/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create New Calendar</a></td>
                <td><a href="/calendar/import" class="btn ubirimi-btn">Import</a></td>
                <td><a id="btnEditCalendar" href="#" class="btn ubirimi-btn disabled"><i class="icon-edit"></i> Edit</a></td>
                <td><a id="btnCalendarSettings" href="#" class="btn ubirimi-btn disabled">Settings</a></td>
                <td><a id="btnShareCalendar" href="#" class="btn ubirimi-btn disabled">Share</a></td>
                <td><a id="btnDeleteCalendar" href="#" class="btn ubirimi-btn disabled"><i class="icon-remove"></i> Delete</a></td>
            </tr>
        </table>

        <?php if ($calendars): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Primary Calendar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr id="table_row_<?php echo $calendar['id'] ?>">
                            <td width="20px">
                                <input type="checkbox" value="1" id="el_check_<?php echo $calendar['id'] ?>"/>
                            </td>
                            <td>
                                <div>
                                    <span style=" width: 24px; background-color: <?php echo $calendar['color'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <a href="/calendar/view/<?php echo $calendar['id'] ?>/<?php echo $month ?>/<?php echo $year ?>"><?php echo $calendar['name'] ?></a>
                                </div>
                            </td>
                            <td>
                                <div><?php echo $calendar['description'] ?></div>
                            </td>
                            <td>
                                <?php if ($calendar['default_flag']): ?>
                                    <div>Yes</div>
                                <?php else: ?>
                                    <div>No</div>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no calendars.</div>
        <?php endif ?>
    </div>

    <div class="ubirimiModalDialog" id="modalDeleteCalendar"></div>
    <div class="ubirimiModalDialog" id="modalShareCalendar"></div>

    <?php require_once __DIR__ . '/_footer.php' ?>
</body>