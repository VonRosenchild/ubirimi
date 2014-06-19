<?php
    use Ubirimi\Container\UbirimiContainer;
    use Ubirimi\Util;

    require_once __DIR__ . '/_header.php';
?>

<div style="font: Trebuchet MS, sans-serif; white-space: nowrap; padding-top: 5px;text-align: left;padding-left: 2px;">
    Hello <?php echo $this->first_name ?> <?php echo $this->last_name ?>
    <br />
    <br />
    You have a new password for your <strong><?php echo $this->repoName ?></strong> SVN Repository.
    <br />
    Bellow you will find the information to access the repository
    <br />
    <br />
    Repository name: <?php echo $this->repoName ?>
    <br />
    Repository URL: <?php echo UbirimiContainer::get()['subversion.url'] . Util::slugify($this->clientData['company_domain']) . '/' . Util::slugify($this->repoName) . '/trunk' ?>
    <br />
    username: <?php echo $this->username ?>
    <br />
    <?php if (null != $this->password): ?>
    password: <?php echo $this->password ?>
    <?php endif ?>
    <br />
    <br />
    The password and access rights (read/write) for the repository can be changed once you are logged in.
</div>

<?php require_once __DIR__ . '/_footer.php' ?>