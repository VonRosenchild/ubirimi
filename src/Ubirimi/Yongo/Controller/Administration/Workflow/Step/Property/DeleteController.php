<?php

use Ubirimi\Util;
use Ubirimi\Yongo\Repository\Workflow\Workflow;

Util::checkUserIsLoggedInAndRedirect();

$propertyId = $_POST['id'];

$this->getRepository('yongo.workflow.workflow')->deleteStepPropertyById($propertyId);