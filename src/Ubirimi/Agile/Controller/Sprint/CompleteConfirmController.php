<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $sprintId = $_GET['id'];
    $boardId = $_GET['board_id'];

    $sprint = AgileSprint::getById($sprintId);
    $lastColumn = AgileBoard::getLastColumn($boardId);
    $completeStatuses = AgileBoard::getColumnStatuses($lastColumn['id'], 'array', 'id');

    $issuesInSprintCount = AgileSprint::getSprintIssuesCount($sprintId);
    $completedIssuesInSprint = AgileSprint::getCompletedIssuesCountBySprintId($sprintId, $completeStatuses);

?>
<div class="headerPageText"><?php echo $sprint['name'] ?></div>
<br />
<div><b><?php echo $completedIssuesInSprint ?></b> issue was Done.</div>
<div><b><?php echo ($issuesInSprintCount - $completedIssuesInSprint) ?></b> incomplete issues will be returned to the top of the backlog.</div>