<?php

use Ubirimi\PasswordHash;
use Ubirimi\Repository\User\User;
use Ubirimi\Util;

Util::checkUserIsLoggedInAndRedirect();

$userId = $_POST['user_id'];
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

$t_hasher = new PasswordHash(8, FALSE);
$user = User::getById($userId);
if (!$t_hasher->CheckPassword($currentPassword, $user['password'])) {
    echo 'current_password_wrong';
} else if ($newPassword != $confirmPassword) {
    echo 'password_mismatch';
} else if (strlen($newPassword) <= 3 || strlen($confirmPassword) <= 3) {
    echo 'password_too_short';
} else {
    $hash = $t_hasher->HashPassword($newPassword);
    User::updatePassword($userId, $hash);
    echo 'ok';
}