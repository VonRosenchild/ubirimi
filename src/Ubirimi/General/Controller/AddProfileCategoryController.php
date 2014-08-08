<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\General\Repository\UserProfileCategory\UserProfileCategory;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $session->set('selected_product_id', -1);
    $menuSelectedCategory = 'general_user';

    if (isset($_POST['new_profile_category'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $description = Util::cleanRegularInputField($_POST['description']);

        if (empty($name))
            $emptyName = true;

        $categoryExists = UserProfileCategory::getByName($clientId, $name);
        if ($categoryExists)
            $alreadyExists = true;

        if (!$emptyName && !$alreadyExists) {
            $date = Util::getServerCurrentDateTime();
            UserProfileCategory::add($clientId, $name, $description, $date);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS, $loggedInUserId, 'ADD Profile Category ' . $name, $date);

            header('Location: /general-settings/users/profile-manager');
        }
    }
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_GENERAL_SETTINGS . ' / Create User Profile Category';

    require_once __DIR__ . '/../Resources/views/AddProfileCategory.php';