<?php require_once __DIR__ . '/../../../GeneralSettings/Resources/views/email/_header.php' ?>

<div style="font: Trebuchet MS, sans-serif; white-space: nowrap; padding-top: 5px;text-align: left;padding-left: 2px;">
    Thank you for registering an account on ubirimi.com
    <br />
    This email confirms that your instance has just been prepared for you.
    <br />
    <br />
    You can use Ubirimi product suite at:
    <br />
    <a href="https://<?php echo $this->companyDomain ?>.ubirimi.net">https://<?php echo $this->companyDomain ?>.ubirimi.net</a>
    <br /><br />

    You can manage your Ubirimi account at:
    <br />
    <a href="https://my.ubirimi.com/sign-in">https://www.ubirimi.com/sign-in</a>
    <br />
    <br />

    The credentials are:
    <br /><br />
    Username: <?php echo $this->username ?>
    <br />
    Password: provided during the sign up process.
    <br /><br /><br />

    For reference, the email address provided during sign up is:
    <br />
    <?php echo $this->emailAddress ?>
    <br /><br />
    Thank you for your interest in Ubirimi and our products.
</div>

<?php require_once __DIR__ . '/../../../GeneralSettings/Resources/views/email/_footer.php' ?>
