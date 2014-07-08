<?php
    use Ubirimi\Repository\HelpDesk\SLACalendar;
    use Ubirimi\Util;

    require_once __DIR__ . '/../../../../../Yongo/Resources/views/_header.php';
?>
<body>

    <?php require_once __DIR__ . '/../../../../../Yongo/Resources/views/_menu.php'; ?>
    <div class="pageContent">
        <?php Util::renderBreadCrumb('<a href="/helpdesk/all">Help Desks</a> > ' . $project['name'] . ' > SLA Calendars'); ?>

        <?php require_once __DIR__ . '/../../../views/_topMenu.php'; ?>

        <table cellspacing="0" border="0" cellpadding="0" class="tableButtons">
            <tr>
                <td>
                    <a href="/helpdesk/sla/calendar/add/<?php echo $projectId ?>" class="btn ubirimi-btn"><i class="icon-plus"></i> Create Calendar</a>
                    <a href="#" class="btn ubirimi-btn" id="btnEditSLACalendar"><i class="icon-edit"></i> Edit</a>
                    <a href="#" id="btnDeleteSLACalendar" class="btn ubirimi-btn"><i class="icon-remove"></i> Delete</a>
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
                                $data = SLACalendar::getCalendarDataByCalendarId($calendar['id']);
                                $dowMap = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
                            ?>
                            <table style="width: 350px">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Working</th>
                                    </tr>
                                </thead>
                                <?php for ($i = 0; $i < count($data); $i++): ?>
                                    <?php if ($data[$i]['not_working_flag']): ?>
                                        <?php continue ?>
                                    <?php endif ?>
                                    <tr>
                                        <td><?php echo $dowMap[$i] ?></td>
                                        <td><?php echo $data[$i]['time_from'] ?></td>
                                        <td><?php echo $data[$i]['time_to'] ?></td>
                                        <td><?php if ($data[$i]['not_working_flag']) echo 'No'; else echo 'Yes'; ?></td>
                                    </tr>
                                <?php endfor ?>
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