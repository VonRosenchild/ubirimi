<?php
    use Ubirimi\Repository\Group\Group;
    use Ubirimi\Util;

    Util::checkUserIsLoggedInAndRedirect();

    $groupId = $_GET['id'];
    $group = Group::getMetadataById($groupId);
?>
<div>Are you sure you want to delete <b><?php echo $group['name'] ?></b> group?</div>
<input type="hidden" value="<?php echo $group['id'] ?>" id="group_id" />