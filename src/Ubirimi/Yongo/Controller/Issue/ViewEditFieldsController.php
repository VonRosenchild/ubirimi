<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();

$issueId = $_POST['issue_id'];
$issueTypeId = $_POST['issue_type_id'];
$issueData = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters(array('issue_id' => $issueId), $loggedInUserId);

require_once __DIR__ . '/../../Resources/views/issue/ViewEditDialog.php';