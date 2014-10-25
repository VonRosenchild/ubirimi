<?php
use Ubirimi\Util;

require_once __DIR__ . '/../_header.php';
?>

<body>

    <?php require_once __DIR__ . '/../_menu.php'; ?>
    <div class="headerPageBackground">
        <table width="100%">
            <tr>
                <td width="48px">
                    <img class="projectIcon" id="project_icon" src="/img/project.png" height="48px" />
                </td>
                <td>
                    <div class="headerPageText"><?php echo $project['name'] ?> > Calendar (due date)</div>
                </td>
            </tr>
        </table>
    </div>
    <div class="pageContent">
        <?php
            $menuProjectCategory = 'calendar';
            require_once __DIR__ . '/_summaryMenu.php';
            require_once __DIR__ . '/_projectButtons.php';
            $previousMonth = $month - 1;
            $previousYear = $year;
            $nextYear = $year;
            if ($previousMonth == 0) {
                $previousMonth = 12;
                $previousYear = $year - 1;
            }
            $nextMonth = $month + 1;
            if ($nextMonth == 13) {
                $nextMonth = 1;
                $nextYear = $year + 1;
            }
        ?>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="33%" align="left"><a href="/yongo/project/calendar/<?php echo $projectId ?>/<?php echo $previousMonth ?>/<?php echo $previousYear ?>" class="btn ubirimi-btn"><?php echo $previousMonthName ?></a></td>
                <td width="33%" align="center"><div class="headerPageText"><?php echo $currentMonthName . ' ' . $year ?></div></td>
                <td width="33%" align="right"><a href="/yongo/project/calendar/<?php echo $projectId ?>/<?php echo $nextMonth ?>/<?php echo $nextYear ?>" class="btn ubirimi-btn"><?php echo $nextMonthName ?></a></td>
            </tr>
        </table>
        <div class="separationVertical"></div>

        <?php echo Util::drawYongoProjectCalendar($projectId, $month, $year, $loggedInUserId); ?>
    </div>
    <?php require_once __DIR__ . '/../_footer.php' ?>
</body>