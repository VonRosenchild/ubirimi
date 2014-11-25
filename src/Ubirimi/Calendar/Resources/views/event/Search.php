<?php

use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>
<body>
    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <?php
        $breadCrumb = '<a href="/calendar/calendars">My Calendars</a> > Search > ' . $query;
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <?php if ($events): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Summary</th>
                        <th>Interval</th>
                        <th>Description</th>
                        <th>Calendar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($event = $events->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <a href="/calendar/event/<?php echo $event['id'] ?>?source=/calendar/search?search_query=<?php echo $query ?>"><?php echo $event['name']; ?></a>
                            </td>
                            <td>
                                <?php if ($event['date_from'] == $event['date_to']): ?>
                                    <?php echo Util::getFormattedDate($event['date_from'], $clientSettings['timezone']) . ', all day'; ?>
                                <?php else: ?>
                                    <?php echo $event['date_from'] . ' - ' . $event['date_to']; ?>
                                <?php endif ?>
                            </td>
                            <td><?php echo $event['description']; ?></td>
                            <td><?php echo $event['calendar_name'] ?></td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no search results.</div>
        <?php endif ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>