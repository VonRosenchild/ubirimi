<?php
    use Ubirimi\Repository\Log;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Issue\IssueLinkType;

    Util::checkUserIsLoggedInAndRedirect();

    $linkTypeId = $_GET['id'];

    $emptyName = false;
    $emptyOutwardDescription = false;
    $emptyInwardDescription = false;
    $linkTypeDuplicateName = false;

    $linkType = IssueLinkType::getById($linkTypeId);

    if ($linkType['client_id'] != $clientId) {
        header('Location: /general-settings/bad-link-access-denied');
        die();
    }

    $name = $linkType['name'];
    $outwardDescription = $linkType['outward_description'];
    $inwardDescription = $linkType['inward_description'];

    if (isset($_POST['edit_link_type'])) {
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
        $existingLinkType = IssueLinkType::getByNameAndClientId($clientId, mb_strtolower($name), $linkTypeId);
        if ($existingLinkType)
            $linkTypeDuplicateName = true;

        if (!$emptyName && !$emptyOutwardDescription && !$emptyInwardDescription && !$linkTypeDuplicateName) {
            $currentDate = Util::getCurrentDateTime($session->get('client/settings/timezone'));
            IssueLinkType::update($linkTypeId, $name, $outwardDescription, $inwardDescription, $currentDate);

            Log::add($clientId, SystemProduct::SYS_PRODUCT_YONGO, $loggedInUserId, 'UPDATE Yongo Issue Link Type ' . $name, $currentDate);

            header('Location: /yongo/administration/issue-features/linking');
        }
    }
    $menuSelectedCategory = 'system';
    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Update Issue Link Type';

    require_once __DIR__ . '/../../../../Resources/views/administration/issue/link/Edit.php';