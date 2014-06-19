<?php
    use Ubirimi\Util;
    use Ubirimi\Yongo\Repository\Workflow\Workflow;

    Util::checkUserIsLoggedInAndRedirect();

    $propertyId = $_POST['id'];

    Workflow::deleteStepPropertyById($propertyId);