<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

    Util::checkUserIsLoggedInAndRedirect();

    $emptyName = false;
    $emptyOutwardDescription = false;
    $emptyInwardDescription = false;
    $linkTypeDuplicateName = false;

    if (isset($_POST['new_link_type'])) {
        $name = Util::cleanRegularInputField($_POST['name']);
        $outwardDescription = Util::cleanRegularInputField($_POST['outward']);
        $inwardDescription = Util::cleanRegularInputField($_POST['inward']);

        if (empty($name))
            $emptyName = true;

        if (empty($outwardDescription))
            $emptyOutwardDescription = true;

        if (empty($inwardDescription))
            $emptyInwardDescription = true;

        // check for duplication
        $linkType = IssueLinkType::getByNameAndClientId($clientId, mb_strtolower($name));
        if ($linkType)
            $linkTypeDuplicateName = true;

        if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueLinkType::add($clientId, $name, $outwardDescription, $inwardDescription, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'ADD Yongo Issue Link Type', $currentDate);

            header('Location: /yongo/administration/issue-features/linking');
        }
    }
    $menuSelectedCategory = 'system';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Create Link Type';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/link/Add.php';