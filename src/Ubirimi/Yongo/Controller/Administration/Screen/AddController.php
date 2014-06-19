<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Field\Field;
    use Ubirimi\Yongo\Repository\Screen\Screen;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;

    $fields = Field::getByClient($clientId);

    if (isset($_POST['add_screen'])) {

        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));

        if (empty($name))
            $emptyName = true;

        if (!$emptyName) {
            $screen = new Screen($clientId, $name, $description);
            $screenId = $screen->save($currentDate);

            $order = 0;
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 6) == 'field_') {
                    $order++;
                    $fieldId = str_replace('field_', '', $key);
                    Screen::addData($screenId, $fieldId, $order, $currentDate);
                }
            }

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Screen ' . $name, $currentDate);

            header('Location: /yongo/administration/screens');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Screen';

    require_once __DIR__ . '/../../../Resources/views/administration/screen/Add.php';