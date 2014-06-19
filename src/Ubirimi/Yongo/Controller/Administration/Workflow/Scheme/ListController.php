<?php
    use Ubirimi\SystemProduct;
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\WorkflowScheme;

    Util::checkUserIsLoggedInAndRedirect();
    $workflowSchemes = WorkflowScheme::getMetaDataByClientId($clientId);
    $menuSelectedCategory = 'issue';

    $sectionPageTitle = $session->get('client/settings/title_name') . ' / ' . SystemProduct::SYS_PRODUCT_YONGO_NAME . ' / Workflow Schemes';

    require_once __DIR__ . '/../../../../Resources/views/administration/workflow/scheme/List.php';