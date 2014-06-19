<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();
    $menuSelectedCategory = 'issue';

    if (isset($_POST['next_step_4'])) {
        $sendEmail = isset($_POST['send_email']) ? 1 : 0;
        UbirimiContainer::get()['session']->set('bulk_change_send_operation_email', $sendEmail);

        header('Location: /yongo/issue/bulk-change-confirmation');
    }

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Bulk: Operation Details';

    require_once __DIR__ . '/../../../Resources/views/issue/bulk/OperationDetails.php';