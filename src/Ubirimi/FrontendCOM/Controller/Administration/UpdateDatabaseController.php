<?php

use Ubirimi\Repository\User\User;

use Ubirimi\Util;


use Ubirimi\Yongo\Repository\Issue\IssueHistory;

//    require_once __DIR__ . '/../web/bootstrap_cli.php';

    Util::checkSuperUserIsLoggedIn();

    $currentDate = Util::getServerCurrentDateTime();

    $issues = \Ubirimi\Yongo\Repository\Issue\Issue::getAll();


    $history = IssueHistory::getAll();

    $clientId = 1936;

    while ($record = $history->fetch_array(MYSQLI_ASSOC)) {
        if ($record['field'] == 'status') {

            $oldResolution = null;
            $newResolution = null;
            $oldResolutionId = null;
            $newResolutionId = null;
            if ($record['old_value'])
                $oldResolution = \Ubirimi\Yongo\Repository\Issue\IssueSettings::getByName($clientId, 'status', $record['old_value']);

            if ($record['new_value'])
                $newResolution = \Ubirimi\Yongo\Repository\Issue\IssueSettings::getByName($clientId, 'status', $record['new_value']);

            if ($oldResolution) {
                $oldResolutionId = $oldResolution['id'];
            }

            if ($newResolution) {
                $newResolutionId = $newResolution['id'];
            }

            IssueHistory::updateChangedIds($record['id'], $oldResolutionId, $newResolutionId);
        }
        if ($record['field'] == 'assignee') {

            $oldUserId = null;
            $newUserId = null;

            $oldUser = null;
            if ($record['old_value'])
                $oldUser = User::getByClientIdAndFullName($clientId, $record['old_value']);

            $newUser = null;
            if ($record['new_value'])
                $newUser = User::getByClientIdAndFullName($clientId, $record['new_value']);

            if ($oldUser) {
                $oldUserId = $oldUser['id'];
            }
            if ($newUser) {
                $newUserId = $newUser['id'];
            }

            IssueHistory::updateChangedIds($record['id'], $oldUserId, $newUserId);
        } else if ($record['field'] == 'resolution') {
            $oldResolution = null;
            $newResolution = null;
            $oldResolutionId = null;
            $newResolutionId = null;
            if ($record['old_value'])
                $oldResolution = \Ubirimi\Yongo\Repository\Issue\IssueSettings::getByName($clientId, 'resolution', $record['old_value']);

            if ($record['new_value'])
                $newResolution = \Ubirimi\Yongo\Repository\Issue\IssueSettings::getByName($clientId, 'resolution', $record['new_value']);

            if ($oldResolution) {
                $oldResolutionId = $oldResolution['id'];
            }

            if ($newResolution) {
                $newResolutionId = $newResolution['id'];
            }

            IssueHistory::updateChangedIds($record['id'], $oldResolutionId, $newResolutionId);
        }
    }