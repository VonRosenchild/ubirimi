<?php
    use Ubirimi\Repository\HelpDesk\SLACalendar;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla_calendar';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks > Create Calendar';

    $emptyName = false;
    $duplicateName = false;

    $projectId = $_GET['id'];

    if (isset($_POST['confirm_new_calendar'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $dataCalendar = array();
        for ($i = 1; $i <= 7; $i++) {
            $dataCalendar[$i - 1]['from_hour'] = $_POST['from_' . $i . '_hour'];
            $dataCalendar[$i - 1]['from_minute'] = $_POST['from_' . $i . '_minute'];
            $dataCalendar[$i - 1]['to_hour'] = $_POST['to_' . $i . '_hour'];
            $dataCalendar[$i - 1]['to_minute'] = $_POST['to_' . $i . '_minute'];
            $dataCalendar[$i - 1]['notWorking'] = isset($_POST['not_working_day_' . $i]) ? $_POST['not_working_day_' . $i] : 0;
        }

        if (!$emptyName && !$duplicateName) {

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            SLACalendar::addCalendar($projectId, $name, $description, $dataCalendar, $currentDate);
            header('Location: /helpdesk/sla/calendar/' . $projectId);
        }
    }

    require_once __DIR__ . '/../../../Resources/views/sla/calendar/Add.php';