<?php
    use Ubirimi\Repository\Documentador\Entity;
    use Ubirimi\Repository\Documentador\EntityType;
    use Ubirimi\Repository\Documentador\Space;
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $emptySpaceName = false;
    $emptySpaceCode = false;
    $doubleCode = false;
    $doubleName = false;

    if (isset($_POST['add_space'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $code = Util::cleanRegularInputField($_POST['code']);
        $description = Util::cleanRegularInputField($_POST['description']);
        $currentDate = Util::getServerCurrentDateTime();

        if (empty($name))
            $emptySpaceName = true;

        if (empty($code))
            $emptySpaceCode = true;

        if (!$emptySpaceName && !$emptySpaceCode) {
            $doubleSpace = Space::getByCodeAndClientId($clientId, $code);
            if ($doubleSpace)
                $doubleCode = true;

            $doubleSpace = Space::getByNameAndClientId($clientId, $name);
            if ($doubleSpace)
                $doubleName = true;

            if (!$doubleCode && !$doubleName) {

                $date = Util::getServerCurrentDateTime();

                $space = new Space($clientId, $loggedInUserId, $name, $code, $description);
                $spaceId = $space->save($currentDate);

                Log::add($clientId, SystemProduct::SYS_PRODUCT_DOCUMENTADOR, $loggedInUserId, 'ADD Documentador space ' . $name, $date);

                // set space permission for current user
                Space::addUserAllPermissions($spaceId, $loggedInUserId);

                // set default home page
                $content = '<p><span style="font-size:24px"><strong>Welcome to your new space!</strong></span></p><div class="message-content" style="font-family: Arial, sans-serif; font-size: 14px;"><p>Documentador spaces are great for sharing content and news with your team. This is your home page. You can customize this page in anyway you like.</p></div>';
                $page = new Entity(EntityType::ENTITY_BLANK_PAGE, $spaceId, $loggedInUserId, null, $name . ' Home', $content);
                $pageId = $page->save($currentDate);
                Space::setHomePageId($spaceId, $pageId);

                // add space permissions for groups
                Space::setDefaultPermissions($clientId, $spaceId);

                header('Location: /documentador/administration/spaces');
            }
        }
    }

    $menuSelectedCategory = 'doc_spaces';

    require_once __DIR__ . '/../../../Resources/views/administration/space/Add.php';