<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\SystemOperation;
    use Ubirimi\Yongo\Repository\Screen\Screen;
    use Ubirimi\Yongo\Repository\Screen\ScreenScheme;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    $allScreens = Screen::getAll($clientId);
    $allOperations = SystemOperation::getAll();

    if (isset($_POST['new_screen_scheme'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $screenId = Util::cleanRegularInputField($_POST['screen']);
        $currentDate = Util::getServerCurrentDateTime();

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $screenScheme = new ScreenScheme($clientId, $name, $description);
            $screenSchemeId = $screenScheme->save($currentDate);
            while ($operation = $allOperations->fetch_array(MYSQLI_ASSOC)) {
                $operationId = $operation['id'];
                ScreenScheme::addData($screenSchemeId, $operationId, $screenId, $currentDate);
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Screen Scheme ' . $name, $currentDate);

            header('Location: /yongo/administration/screens/schemes');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen Scheme';
    require_once __DIR__ . '/../../../../Resources/views/administration/screen/scheme/Add.php';