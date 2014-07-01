<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueWorkLog;

    Util::checkUserIsLoggedInAndRedirect();

    $workLogId = $_GET['work_log_id'];
    $remainingEstimate = $_GET['remaining'];
    $remainingEstimate = trim(str_replace(array('w', 'd', 'h', 'm'), array('w ', 'd ', 'h ', 'm'), $remainingEstimate));

    $workLog = IssueWorkLog::getWorkLogById($workLogId);

    $mode = 'edit';
    require_once __DIR__ . '/../../../Resources/views/issue/log_work/AddDialog.php';

    echo '<input type="hidden" value="' . $workLogId . '" id="work_log_id" />';