<?php
    use Ubirimi\Agile\Repository\AgileBoard;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Project\Project;

    Util::checkUserIsLoggedInAndRedirect();

    $menuSelectedCategory = 'agile';
    $projects = Project::getByClientId($clientId);

    $boardId = $_GET['id'];
    $board = AgileBoard::getById($boardId);

    if ($board['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $emptyName = false;
    $boardName = $board['name'];
    $boardDescription = $board['description'];

    if (isset($_POST['confirm_new_board'])) {
        $boardName = Util::cleanRegularInputField($_POST['name']);
        $boardDescription = Util::cleanRegularInputField($_POST['description']);

        if (empty($boardName))
            $emptyName = true;

        if (!$emptyName) {

            $date = Util::getCurrentDateTime($session->get('client/settings/timezone'));

            AgileBoard::updateMetadata($clientId, $boardId, $boardName, $boardDescription, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_CHEETAH, $loggedInUserId, 'UPDATE Cheetah Agile Board ' . $boardName, $date);

            header('Location: /agile/boards');
        }
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_CHEETAH_NAME. ' / Update Board';

    require_once __DIR__ . '/../../Resources/views/board/Edit.php';