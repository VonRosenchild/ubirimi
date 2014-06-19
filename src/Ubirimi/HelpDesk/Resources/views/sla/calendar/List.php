<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb('<a href="/helpdesk/all">Help Desks</a> > SLAs > Calendars'); ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/helpdesk/sla/calendars/add" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Calendar</a>
                    <a href="/helpdesk/sla/edit/<?php echo $slaSelectedId?>" class="btn ubirimi-btn"><i class="icon-edit"></i> Edit</a>
                    <a href="#" id="btnDeleteSLA" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a>
                </td>
            </tr>
        </table>

        <?php if ($calendars): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($calendar = $calendars->fetch_array(MYSQLI_ASSOC)): ?>
                    <tr id="table_row_<?php echo $calendar['id'] ?>">
                        <td width="22">
                            <input type="checkbox" value="1" id="el_check_<?php echo $calendar['id'] ?>" />
                        </td>
                        <td>
                            <?php echo $calendar['name']; ?>
                        </td>
                        <td><?php echo $calendar['description']; ?></td>
                        <td>
                            <?php
                                $data = SLA::getCalendarDataByCalendarId($calendar['id']);
                                $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                            ?>
                            <table class="table table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                    </tr>
                                </thead>
                                <?php while ($dayData = $data->fetch_array(MYSQLI_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $dowMap[$dayData['help_sla_calendar_day_id']] ?></td>
                                        <td><?php echo $dayData['start_time'] ?></td>
                                        <td><?php echo $dayData['end_time'] ?></td>
                                        <td><?php echo $dayData['not_working_flag'] ?></td>
                                    </tr>
                                <?php endwhile ?>
                            </table>
                        </td>
                    </tr>
                <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div>There are no Calendars created.</div>
        <?php endif ?>
    </div>
    <div id="modalDeleteSLACalendar"></div>
    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_footer.php' ?>
</body>