<?php
use Ubirimi\Util;

require_once __DIR__ . '/_header.php';
$httpHOST = Util::getHttpHost();
?>

<div style="color: #333333; font: 17px Trebuchet MS, sans-serif; white-space: nowrap; padding-top: 5px;text-align: left;padding-left: 2px;">
    Hello <?php echo $this->firstName . ' ' . $this->lastName ?>
    <br />
    A new account has been created for you.
    <br /><br />
    You can log in at:
    <br />
    <?php if (isset($this->isCustomer) && $this->isCustomer): ?>
        <a href="<?php echo $httpHOST ?>/helpdesk/customer-portal"><?php echo $httpHOST ?>/helpdesk/customer-portal</a>
    <?php else: ?>
        <a href="<?php echo $httpHOST ?>"><?php echo $httpHOST ?></a>
    <?php endif ?>

    <br />
    with the following credentials:
    <br /><br />
    <?php if (isset($this->isCustomer) && $this->isCustomer): ?>
    email address: <?php echo $this->email ?>
    <?php else: ?>
    username: <?php echo $this->username ?>
    <?php endif ?>
    <br />
    password: <?php echo $this->password ?>
    <br /><br />
    The password can be changed once you are logged in.
</div>

<?php require_once __DIR__ . '/_footer.php' ?>