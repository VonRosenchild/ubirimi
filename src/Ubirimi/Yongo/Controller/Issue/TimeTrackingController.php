<?php
use Ubirimi\Container\UbirimiContainer;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();
$issueId = $_POST['id'];

$issueQueryParameters = array('issue_id' => $issueId);
$issue = UbirimiContainer::getRepository('yongo.issue.issue')->getByParameters($issueQueryParameters, $loggedInUserId);

$hoursPerDay = $session->get('yongo/settings/time_tracking_hours_per_day');
$daysPerWeek = $session->get('yongo/settings/time_tracking_days_per_week');

require_once __DIR__ . '/../../Resources/views/issue/_timeTrackingInformation.php';