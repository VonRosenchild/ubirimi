<?php
use Ubirimi\Container\UbirimiContainer;

$session = UbirimiContainer::get()['session'];
?>
<div style="background-color: #ffffff; border-radius: 5px; border: #CCCCCC 1px solid; padding: 10px; margin: 10px;">
    <?php require __DIR__ . '/_header.php'; ?>
    <div style="padding-top: 5px; color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;text-align: left;padding-left: 2px;">
        <a style="text-decoration: none; " href="<?php echo $session->get('client/base_url') ?>/yongo/issue/<?php echo $this->issue['id'] ?>"><?php echo $this->issue['summary'] ?></a>
        <br />
    </div>

    <div style="height: 10px"></div>

    <div>Project: <a href="<?php echo $session->get('client/base_url') ?>/yongo/project/<?php echo $this->project['id'] ?>"><?php echo $this->project['name'] ?></a></div>
    <div>New comment by: <a href="<?php echo $session->get('client/base_url') ?>/yongo/user/profile/<?php echo $this->user['id'] ?>"><?php echo $this->user['first_name'] . ' ' . $this->user['last_name'] ?></a></div>

    <div><?php echo str_replace("\n", '<br />', $this->content) ?></div>
</div>

<?php require '_footer.php' ?>