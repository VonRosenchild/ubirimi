<?php
    use Ubirimi\Repository\HelpDesk\SLA;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $clientSettings = $session->get('client/settings');

    $menuSelectedCategory = 'help_desk';
    $menuProjectCategory = 'sla';
    $sectionPageTitle = $clientSettings['title_name'] . ' / ' . SystemProduct::SYS_PRODUCT_HELP_DESK_NAME . ' / Help Desks';

    $emptyName = false;
    $duplicateName = false;

    if (isset($_POST['confirm_new_calendar'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name)) {
            $emptyName = true;
        }

        $dataCalendar = array();
        for ($i = 0; $i < 7; $i++) {
            $dataCalendar[$i]['from_hour'] = $_POST['from_' . $i . '_hour'];
            $dataCalendar[$i]['from_minute'] = $_POST['from_' . $i . '_minute'];
            $dataCalendar[$i]['to_hour'] = $_POST['to_' . $i . '_hour'];
            $dataCalendar[$i]['to_minute'] = $_POST['to_' . $i . '_minute'];
            $dataCalendar[$i]['notWorking'] = isset($_POST['not_working_day_' . $i]) ? $_POST['not_working_day_' . $i] : 0;
        }

        if (!$emptyName && !$duplicateName) {

            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            SLA::addCalendar($clientId, $name, $description, $dataCalendar, $currentDate);
            header('Location: /helpdesk/sla/calendars');
        }
    }

    require_once __DIR__ . '/../../../Resources/views/sla/calendar/Add.php';