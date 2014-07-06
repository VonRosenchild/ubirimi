<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueSettings;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyPriorityName = false;
    $priorityExists = false;

    if (isset($_POST['new_priority'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $color = Util::cleanRegularInputField($_POST['color']);

        if (empty($name))
            $emptyPriorityName = true;

        // check for duplication
        $priority = IssueSettings::getByName($clientId, 'priority', mb_strtolower($name));
        if ($priority)
            $priorityExists = true;

        if (!$priorityExists && !$emptyPriorityName) {
            $iconName = 'generic.png';
            $currentDate = Util::getServerCurrentDateTime();
            IssueSettings::create(
                'issue_priority',
                $clientId,
                $name,
                $description,
                $iconName,
                $color,
                $currentDate
            );

            Log::add(
                $clientId,
                SystemProduct::SYS_PRODUCT_YONGO,
                $loggedInUserId,
                'ADD Yongo Issue Priority ' . $name,
                $currentDate
            );

            header('Location: /yongo/administration/issue/priorities');
        }
    }

    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Issue Priority';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/priority/Add.php';