<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
?>
<body>
    <?php require_once __DIR__ . '/_menu.php'; ?>
    <?php
        $breadCrumb = 'Shared With Me Calendars';
        Util::renderBreadCrumb($breadCrumb);
    ?>

    <div class="pageContent">
        <?php if ($calendarsSharedWithMe): ?>
            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($calendar = $calendarsSharedWithMe->fetch_array(MYSQLI_ASSOC)): ?>
                        <tr>
                            <td>
                                <div><a href="/calendar/view/<?php echo $calendar['id'] ?>/<?php echo $month ?>/<?php echo $year ?>"><?php echo $calendar['name'] ?></a></div>
                            </td>
                            <td>
                                <div><?php echo $calendar['description'] ?></div>
                            </td>
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="infoBox">There are no calendars shared with you.</div>
        <?php endif ?>
    </div>

    <?php require_once __DIR__ . '/_footer.php' ?>
</body>