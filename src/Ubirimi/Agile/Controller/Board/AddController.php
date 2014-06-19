<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Repository\Client;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueFilter;
    use Ubirimi\Yongo\Repository\Permission\Permission;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';
    $projects = Client::getProjectsByPermission($session->get('client/id'), $loggedInUserId, Permission::PERM_BROWSE_PROJECTS);

    $noProjectSelected = false;
    $emptyName = false;

    if (isset($_POST['confirm_new_board'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $projectsInBoard = isset($_POST['project']) ? $_POST['project'] : null;

        if (!$projectsInBoard)
            $noProjectSelected = true;

        if (empty($name))
            $emptyName = true;

        if (!$emptyName && !$noProjectSelected) {
            $definitionData = 'project=' . implode('|', $projectsInBoard);
            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $filterId = IssueFilter::save($loggedInUserId, 'Filter for ' . $name, 'Filter created automatically for agile board ' . $name, $definitionData, $date);
            $board = new AgileBoard($clientId, $filterId, $name, $description, $projectsInBoard);
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            $boardId = $board->save($loggedInUserId, $currentDate);
            $board->addDefaultColumnData($clientId, $boardId);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $loggedInUserId, 'ADD Cheetah Agile Board ' . $name, $date);

            header('Location: /agile/boards');
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Create Board';

    require_once __DIR__ . '/../../Resources/views/board/Add.php';