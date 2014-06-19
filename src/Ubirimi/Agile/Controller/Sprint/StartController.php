<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Agile\Repository\AgileSprint;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $sprintId = $_POST['id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $name = $_POST['name'];

    AgileSprint::start($sprintId, $startDate, $endDate, $name);